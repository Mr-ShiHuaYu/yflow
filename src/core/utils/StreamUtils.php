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


/**
 * Stream 流工具类（PHP 版本使用数组函数实现）
 *
 *
 */
class StreamUtils
{
    private function __construct()
    {
    }

    /**
     * 将数组过滤，返回第一个匹配的元素
     *
     * @param array|null $collection 需要转化的数组
     * @param callable $function 过滤方法
     * @return mixed 过滤后的第一个元素
     */
    public static function filterOne(?array $collection, callable $function): mixed
    {
        if (CollUtil::isEmpty($collection)) {
            return null;
        }
        foreach ($collection as $item) {
            if ($function($item)) {
                return $item;
            }
        }
        return null;
    }

    /**
     * 将数组拼接
     *
     * @param array $collection 需要转化的数组
     * @param callable $function 拼接方法
     * @param string $delimiter 拼接符
     * @return string 拼接后的字符串
     */
    public static function join(array $collection, callable $function, string $delimiter = ','): string
    {
        if (CollUtil::isEmpty($collection)) {
            return '';
        }
        $result = array_filter(array_map($function, $collection), fn($item) => $item !== null);
        return implode($delimiter, $result);
    }

    /**
     * 将数组过滤
     *
     * @param array|null $collection 需要转化的数组
     * @param callable $function 过滤方法
     * @return array 过滤后的数组
     */
    public static function filter(?array $collection, callable $function): array
    {
        if (CollUtil::isEmpty($collection)) {
            return [];
        }
        return array_values(array_filter($collection, $function));
    }

    /**
     * 将数组排序
     *
     * @param array|null $collection 需要转化的数组
     * @param callable $comparing 排序方法
     * @return array 排序后的数组
     */
    public static function sorted(?array $collection, callable $comparing): array
    {
        if (CollUtil::isEmpty($collection)) {
            return [];
        }
        usort($collection, $comparing);
        return array_values(array_filter($collection, fn($item) => $item !== null));
    }

    /**
     * 将数组转化为 map
     *
     * @param array $collection 需要转化的数组
     * @param callable $key key 转换方法
     * @param callable|null $value value 转换方法
     * @return array 转化后的 map
     */
    public static function toMap(array $collection, callable $key, ?callable $value = null): array
    {
        if (CollUtil::isEmpty($collection)) {
            return [];
        }

        $result = [];
        foreach ($collection as $item) {
            if ($item === null) {
                continue;
            }
            if (is_object($item)) {
                $k          = $key($item);
                $v          = $value ? $value($item) : $item;
                $result[$k] = $v;
            }
        }
        return $result;
    }

    /**
     * 将数组按照规则分类成 map
     *
     * @param array $collection 需要分类的数组
     * @param callable $key 分类的规则
     * @return array 分类后的 map
     */
    public static function groupByKey(array $collection, callable $key): array
    {
        if (CollUtil::isEmpty($collection)) {
            return [];
        }

        $result = [];
        foreach ($collection as $item) {
            if ($item === null) {
                continue;
            }
            if (is_object($item)) {
                $k            = $key($item);
                $result[$k][] = $item;
            }
        }
        return $result;
    }

    /**
     * 将数组按照规则和过滤器分类成 map
     *
     * @param callable $predicate 过滤器
     * @param array|null $collection 需要分类的数组
     * @param callable $key 分类的规则
     * @return array 分类后的 map
     */
    public static function groupByKeyFilter(callable $predicate, ?array $collection, callable $key): array
    {
        if (CollUtil::isEmpty($collection)) {
            return [];
        }

        $result = [];
        foreach ($collection as $item) {
            if (!$predicate($item)) {
                continue;
            }
            $k            = $key($item);
            $result[$k][] = $item;
        }
        return $result;
    }

    /**
     * 将数组转化为 List，泛型可以不同
     *
     * @param array|null $collection 需要转化的数组
     * @param callable $function 转换方法
     * @return array 转化后的 list
     */
    public static function toList(?array $collection, callable $function): array
    {
        if (CollUtil::isEmpty($collection)) {
            return [];
        }
        return array_map($function, $collection);
    }

    /**
     * 将数组转化为 Set
     *
     * @param array|null $collection 需要转化的数组
     * @param callable|null $function 转换方法
     * @return array 转化后的 set
     */
    public static function toSet(?array $collection, ?callable $function): array
    {
        if (CollUtil::isEmpty($collection) || $function === null) {
            return [];
        }
        $result = array_map($function, $collection);
        return array_values(array_unique(array_filter($result, fn($item) => $item !== null)));
    }

    /**
     * 合并两个相同 key 类型的 map
     *
     * @param array|null $map1 第一个需要合并的 map
     * @param array|null $map2 第二个需要合并的 map
     * @param callable $merge 合并方法
     * @return array 合并后的 map
     */
    public static function merge(?array $map1, ?array $map2, callable $merge): array
    {
        if (MapUtil::isEmpty($map1) && MapUtil::isEmpty($map2)) {
            return [];
        } elseif (MapUtil::isEmpty($map1)) {
            $map1 = [];
        } elseif (MapUtil::isEmpty($map2)) {
            $map2 = [];
        }

        $keys   = array_unique(array_merge(array_keys($map1), array_keys($map2)));
        $result = [];

        foreach ($keys as $k) {
            $x = $map1[$k] ?? null;
            $y = $map2[$k] ?? null;
            $z = $merge($x, $y);
            if ($z !== null) {
                $result[$k] = $z;
            }
        }
        return $result;
    }

    /**
     * 将数组转化为 List，每个元素转换为列表后扁平化
     *
     * @param array|null $collection 需要转化的数组
     * @param callable $function 转换方法，将每个元素转换为列表
     * @return array 转化后的 list
     */
    public static function toListAll(?array $collection, callable $function): array
    {
        if (CollUtil::isEmpty($collection)) {
            return [];
        }
        $result = [];
        foreach ($collection as $item) {
            $list = $function($item);
            if (is_array($list)) {
                $result = array_merge($result, $list);
            }
        }
        return $result;
    }
}
