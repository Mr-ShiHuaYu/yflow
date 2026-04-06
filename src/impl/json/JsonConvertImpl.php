<?php

namespace Yflow\impl\json;

use ReflectionException;
use RuntimeException;
use Yflow\core\json\JsonConvert;
use Yflow\core\utils\DtoUtil;

class JsonConvertImpl implements JsonConvert
{
    /**
     * 将字符串转为 map
     *
     * @param string|null $jsonStr json 字符串
     * @return array<string, mixed>
     */
    public function strToMap(?string $jsonStr): array
    {
        if (empty($jsonStr)) {
            return [];
        }
        $result = json_decode($jsonStr, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('json 转换异常：' . json_last_error_msg());
        }

        return is_array($result) ? $result : [];
    }

    /**
     * 将字符串转为 bean
     *
     * @template T
     * @param string $jsonStr json 字符串
     * @param string $clazz 类名
     * @return object|null
     * @throws ReflectionException
     */
    public function strToBean(string $jsonStr, string $clazz): ?object
    {
        $data = json_decode($jsonStr, true);
        return DtoUtil::fromData($data, $clazz);
    }

    /**
     * 将字符串转为集合
     * TODO 未测试
     *
     * @template T
     * @param string $jsonStr json 字符串
     * @return array<T>|null
     */
    public function strToList(string $jsonStr): ?array
    {
        if (empty($jsonStr)) {
            return null;
        }
        $result = json_decode($jsonStr, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('json 转换异常：' . json_last_error_msg());
        }

        return is_array($result) ? $result : null;
    }

    /**
     * 将对象转为 json 字符串
     *
     * @param mixed $obj 对象
     * @return string
     */
    public function toJson(mixed $obj): string
    {
        if (is_object($obj) && method_exists($obj, 'toArray')) {
            $obj = $obj->toArray();
        }
        if (is_array($obj)) {
            $obj = $this->filterNullValues($obj);
        }
        return json_encode($obj, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 将对象转为字符串
     *
     * @param mixed $variable 对象
     * @return string|null json 字符串
     */
    public function objToStr(mixed $variable): ?string
    {
        if (empty($variable)) {
            return null;
        }
        if (is_object($variable) && method_exists($variable, 'toArray')) {
            $variable = $variable->toArray();
        }
        if (is_array($variable)) {
            $variable = $this->filterNullValues($variable);
        }
        return json_encode($variable, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 递归过滤掉数组中的 null 值
     * @param array|null $data
     * @return array|null
     */
    private function filterNullValues(?array $data): ?array
    {
        if (is_null($data)) {
            return null;
        }
        $filtered = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $filteredValue = $this->filterNullValues($value);
                if (!is_null($filteredValue)) {
                    $filtered[$key] = $filteredValue;
                }
            } elseif (!is_null($value)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    /**
     * 将对象转为 map
     *
     * @param object $obj 对象
     * @return array<string, mixed>
     */
    public function toMap(object $obj): array
    {
        if (method_exists($obj, 'toArray')) {
            $data = $obj->toArray();
        } else {
            $data = (array)$obj;
        }

        return $data;
    }

    /**
     * 将 map 转为 bean
     *
     * @template T
     * @param array $data 数据
     * @param string $clazz 类名
     * @return object|null
     * @throws ReflectionException
     */
    public function mapToBean(array $data, string $clazz): object|null
    {
        return DtoUtil::fromData($data, $clazz);
    }
}
