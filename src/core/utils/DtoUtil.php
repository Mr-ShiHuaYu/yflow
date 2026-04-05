<?php

namespace Yflow\core\utils;

use ReflectionClass;
use ReflectionException;

class DtoUtil
{
    /**
     * 从数据创建对象
     * @param array $data
     * @param string $className
     * @param bool $trim 是否对字符串值进行 trim
     * @param bool $ignoreNull 是否忽略 null 值
     * @param bool $ignoreEmpty 是否忽略空字符串
     * @return object
     * @throws ReflectionException
     */
    public static function fromData(array $data, string $className, bool $trim = false, bool $ignoreNull = false, bool $ignoreEmpty = false): object
    {
        // 数据预处理
        if ($trim) {
            $data = array_map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            }, $data);
        }

        if ($ignoreNull || $ignoreEmpty) {
            $data = array_filter($data, function ($value) use ($ignoreNull, $ignoreEmpty) {
                if ($ignoreNull && $value === null) {
                    return false;
                }
                if ($ignoreEmpty && $value === '') {
                    return false;
                }
                return true;
            });
        }

        $reflection = new ReflectionClass($className);
        $instance   = $reflection->newInstance();

        foreach ($data as $key => $value) {
            // 尝试直接匹配
            $setter = 'set' . ucfirst($key);
            if (method_exists($instance, $setter)) {
                $processedValue = self::processValue($value, $reflection, $key);
                $instance->{$setter}($processedValue);
            } elseif (property_exists($instance, $key)) {
                $processedValue = self::processValue($value, $reflection, $key);
                $property       = $reflection->getProperty($key);
                $property->setValue($instance, $processedValue);
            } else {
                // 尝试将 snake_case 转换为 camelCase 后匹配
                $camelKey    = self::snakeToCamel($key);
                $camelSetter = 'set' . ucfirst($camelKey);
                if (method_exists($instance, $camelSetter)) {
                    $processedValue = self::processValue($value, $reflection, $camelKey);
                    $instance->{$camelSetter}($processedValue);
                } elseif (property_exists($instance, $camelKey)) {
                    $processedValue = self::processValue($value, $reflection, $camelKey);
                    $property       = $reflection->getProperty($camelKey);
                    $property->setValue($instance, $processedValue);
                }
            }
        }

        return $instance;
    }

    /**
     * 处理属性值，递归创建对象
     * @param mixed $value
     * @param ReflectionClass $reflection
     * @param string $propertyName
     * @return mixed
     * @throws ReflectionException
     */
    private static function processValue(mixed $value, ReflectionClass $reflection, string $propertyName): mixed
    {
        if (!property_exists($reflection->getName(), $propertyName)) {
            return $value;
        }

        $property   = $reflection->getProperty($propertyName);
        $docComment = $property->getDocComment();

        if (!$docComment) {
            return $value;
        }

        $comment = (string)str_replace("\r\n", "\n", $docComment);
        $comment = (string)preg_replace('/\*\/[ \t]*$/', '', $comment); // strip '*/'
        preg_match('/@var\s+(?<type>\S+)([ \t])?(?<description>.+)?$/im', $comment, $matches);

        if (!isset($matches['type'])) {
            return $value;
        }

        $types = array_filter(explode('|', $matches['type']));

        foreach ($types as $type) {
            if (
                $type === 'array' // 纯 array 类型，无意义
                || str_starts_with($type, ' ') // 以空格开头的，是被 array<string, string> 这种切掉的，忽略掉
            ) {
                continue;
            }
            if (str_ends_with($type, '[]')) {
                // 处理 string[] 或 ClassName[] 或 ClassName[][] 等多维数组的解析
                $itemType = substr($type, 0, -2);
                // 递归处理多层 [] 的情况
                while (str_ends_with($itemType, '[]')) {
                    $itemType = substr($itemType, 0, -2);
                }
                $fullItemType = self::getFullClassName($itemType, $reflection);
                if (class_exists($fullItemType) && is_array($value)) {
                    return self::processObjectArray($value, $fullItemType);
                }
            } elseif (str_starts_with($type, 'array<')) {
                // 处理 array<string, ClassName|xxx> 类型的解析
                // 重新以 comment 提取 array<> 中的内容，因为上方的正则会以空格切开，导致 $type 信息不全
                preg_match('/array<(.*)>/m', $comment, $matches);
                $itemType = $matches[1] ?? null;
                if (!$itemType) {
                    continue;
                }
                if (!str_contains($itemType, ',')) {
                    // array<int|string>  或 array<int> 的形式
                    if (str_contains($itemType, '|')) {
                        // array<int|string> 不支持多类型的情况
                        continue;
                    }
                    $fullItemType = self::getFullClassName($itemType, $reflection);
                    if (class_exists($fullItemType) && is_array($value)) {
                        return self::processObjectArray($value, $fullItemType);
                    }
                } else {
                    [, $valueType] = explode(', ', $itemType);
                    $valueType = trim($valueType);
                    // array<string, string|null> 检测和支持
                    $nullable = str_contains($valueType, '|null') || str_contains($valueType, 'null|');
                    if ($nullable) {
                        // 去掉 null
                        $valueType = str_replace(['|null', 'null|'], '', $valueType);
                    }
                    if (str_contains($valueType, '|')) {
                        // array<int|string, ClassName|xxx> 不支持多类型的情况
                        continue;
                    }
                    // 检测 array<string, ClassName[]> 或 array<string, ClassName[][]> 的情况（value 本身是数组）
                    $nestedLevel = 0;
                    $itemType    = $valueType;
                    while (str_ends_with($itemType, '[]')) {
                        $itemType = substr($itemType, 0, -2);
                        $nestedLevel++;
                    }
                    if ($nestedLevel > 0) {
                        $fullItemType = self::getFullClassName($itemType, $reflection);
                        if (class_exists($fullItemType) && is_array($value)) {
                            $result = [];
                            foreach ($value as $key => $item) {
                                if (is_array($item)) {
                                    $nestedResult = [];
                                    foreach ($item as $nestedItem) {
                                        if (is_array($nestedItem)) {
                                            $nestedResult[] = self::fromData($nestedItem, $fullItemType);
                                        } else {
                                            $nestedResult[] = $nestedItem;
                                        }
                                    }
                                    $result[$key] = $nestedResult;
                                } else {
                                    $result[$key] = $item;
                                }
                            }
                            return $result;
                        }
                    }
                    $fullItemType = self::getFullClassName($valueType, $reflection);
                    if (class_exists($fullItemType) && is_array($value)) {
                        $result = [];
                        foreach ($value as $key => $item) {
                            if (is_array($item)) {
                                $result[$key] = self::fromData($item, $fullItemType);
                            } else {
                                $result[$key] = $item;
                            }
                        }
                        return $result;
                    }
                }
            } elseif (is_array($value)) {
                // 处理单个对象类型
                $fullType = self::getFullClassName($type, $reflection);
                if (class_exists($fullType)) {
                    return self::fromData($value, $fullType);
                }
            }
        }

        return $value;
    }

    /**
     * 处理对象数组
     * @param array $value
     * @param string $fullItemType
     * @return array
     * @throws ReflectionException
     */
    private static function processObjectArray(array $value, string $fullItemType): array
    {
        $result = [];
        foreach ($value as $item) {
            if (is_array($item)) {
                $result[] = self::fromData($item, $fullItemType);
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * 将 snake_case 转换为 camelCase
     * @param string $string
     * @return string
     */
    private static function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    /**
     * 获取完整的类名（包含命名空间）
     * @param string $type
     * @param ReflectionClass $reflectionClass
     * @return string
     */
    private static function getFullClassName(string $type, ReflectionClass $reflectionClass): string
    {
        // 如果已经是完全限定名，直接返回
        if (str_starts_with($type, '\\')) {
            return ltrim($type, '\\');
        }
        // 非 \ 开头的
        /** @phpstan-ignore-next-line */
        $content = file_get_contents($reflectionClass->getFileName());
        assert($content !== false);
        // 从 use 里提取
        preg_match('/use\s+((.*)\\\\' . $type . ');$/m', $content, $matches);
        if (isset($matches[1])) {
            /** @phpstan-ignore-next-line */
            return $matches[1];
        }
        // 从 use Xxx as Xxx 里提取
        preg_match('/use\s+(.*)\s+as\s+' . $type . ';$/m', $content, $matches);
        if (isset($matches[1])) {
            /** @phpstan-ignore-next-line */
            return $matches[1];
        }
        // use 里没有的，与当前类同 namespace
        $className = $reflectionClass->getNamespaceName() . '\\' . $type;
        if (class_exists($className)) {
            return $className;
        }

        return $type;
    }
}
