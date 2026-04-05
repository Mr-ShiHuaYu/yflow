<?php
/*
 *    Copyright 2026, Y-Flow (974988176@qq.com).
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *       https://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace Yflow\core\utils;

use RuntimeException;
use stdClass;

/**
 * 数组工具类
 *
 *
 * @since 2023/5/18 9:45
 */
class ArrayUtil
{
    /**
     * 判断一个对象数组是否为空
     *
     * @param array|null $objects 要判断的对象数组
     * @return bool true：为空 false：非空
     */
    public static function isEmpty(?array $objects): bool
    {
        return $objects === null || count($objects) === 0;
    }

    /**
     * 判断一个对象数组是否非空
     *
     * @param array|null $objects 要判断的对象数组
     * @return bool true：非空 false：空
     */
    public static function isNotEmpty(?array $objects): bool
    {
        return !self::isEmpty($objects);
    }

    /**
     * 如果数组是空，则返回默认值
     *
     * @param array|null $objects 数组
     * @param array $defaultArr 默认值
     * @return array 结果
     */
    public static function emptyDefault(?array $objects, array $defaultArr): array
    {
        return self::isEmpty($objects) ? $defaultArr : $objects;
    }

    /**
     * 字符串转数组
     *
     * @param string|null $str 字符串
     * @param string $sep 分隔符
     * @return array|null
     */
    public static function strToArray(?string $str, string $sep): ?array
    {
        if (StringUtils::isEmpty($str)) {
            return null;
        }
        return explode($sep, $str);
    }


    /**
     * 多维数组转对象
     *
     * @param array $array $array
     * @param string $clazz 类名，默认值为 \stdClass::class
     * @return stdClass
     */
    public static function arrayToObject(array $array, string $clazz = stdClass::class): object
    {
        // 如果类存在，实例化对象
        if (class_exists($clazz)) {
            $object = new $clazz();
        } else {
            $object = new stdClass();
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // 递归处理子数组
                $nestedValue = self::arrayToObject($value);
                // $nestedValue = $value;
            } else {
                if (is_numeric($value)) {
                    $nestedValue = (float)$value; // 将字符串转换为数字类型
                } else {
                    $nestedValue = $value;
                }
            }

            // 尝试使用set方法设置属性
            $setter = 'set' . ucfirst($key);
            if (method_exists($object, $setter)) {
                $object->$setter($nestedValue);
            } else {
                // 直接设置属性
                $object->$key = $nestedValue;
            }
        }
        return $object;
    }

    /**
     * json转array
     *
     * @param string $jsonStr json 字符串
     * @return array
     */
    public static function jsonToArray(string $jsonStr): array
    {
        if (empty($jsonStr)) {
            throw new RuntimeException('json 字符串为空');
        }

        $data = json_decode($jsonStr, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('json 转换异常：' . json_last_error_msg());
        }

        if (!is_array($data)) {
            throw new RuntimeException('json 转换异常：不是数组');
        }

        return $data;
    }

}
