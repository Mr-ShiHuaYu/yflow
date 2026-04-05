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
 * 集合工具类
 *
 *
 * @since 2023/5/18 9:39
 */
class CollUtil
{
    /**
     * 获取集合的第一个
     *
     * @param array $list 集合
     * @return mixed
     */
    public static function getOne(array $list): mixed
    {
        if (empty($list)) {
            return null;
        }
        return reset($list);
    }

    /**
     * 如果集合是空，则返回空集合
     *
     * @param array|null $list 集合
     * @return array 结果
     */
    public static function emptyDefault(?array $list): array
    {
        return self::isEmpty($list) ? [] : $list;
    }

    /**
     * 判断一个数组是否为空
     *
     * @param array|null $coll 要判断的数组
     * @return bool true：为空 false：非空
     */
    public static function isEmpty(?array $coll): bool
    {
        return $coll === null || count($coll) === 0;
    }

    /**
     * 如果集合是空，则返回默认值
     *
     * @param array|null $list 集合
     * @param array $defaultList 默认值
     * @return array 结果
     */
    public static function emptyDefaultWith(?array $list, array $defaultList): array
    {
        return self::isEmpty($list) ? $defaultList : $list;
    }

    /**
     * 判断给定的数组 array 中是否包含给定的元素 value
     *
     * @param array|null $collection 给定的数组
     * @param array $array 给定的数组
     * @return bool 结果
     */
    public static function containsAny(?array $collection, array $array): bool
    {
        if (self::isEmpty($collection) || ArrayUtil::isEmpty($array)) {
            return false;
        }
        foreach ($array as $str) {
            if (in_array($str, $collection)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断给定的 collection1 列表中是否包含 collection2
     * 判断给定的 collection2 中是否完全不包含给定的元素 value
     *
     * @param array|null $collection1 给定的集合 1
     * @param array|null $collection2 给定的集合 2
     * @return bool 结果
     */
    public static function notContainsAny(?array $collection1, ?array $collection2): bool
    {
        return !self::containsAnyCollection($collection1, $collection2);
    }

    /**
     * 判断给定的 collection1 列表中是否包含 collection2
     * 判断给定的 collection2 中是否包含给定的元素 value
     *
     * @param array|null $collection1 给定的集合 1
     * @param array|null $collection2 给定的集合 2
     * @return bool 结果
     */
    public static function containsAnyCollection(?array $collection1, ?array $collection2): bool
    {
        if (self::isEmpty($collection1) || self::isEmpty($collection2)) {
            return false;
        }
        return !empty(array_intersect($collection2, $collection1));
    }

    /**
     * 字符串转数组
     *
     * @param string|null $str 字符串
     * @param string $sep 分隔符
     * @return array|null
     */
    public static function strToColl(?string $str, string $sep): ?array
    {
        if (StringUtils::isEmpty($str)) {
            return null;
        }
        return explode($sep, $str);
    }

    /**
     * 集合 add 新的对象，返回新的集合
     *
     * @param array|null $list 集合
     * @param array|null $t 对象
     * @return array
     */
    public static function listAddToNew(?array $list, ?array $t): array
    {
        return self::listAddToList($list, [$t]);
    }

    /**
     * 集合 add 新的对象，返回新的集合
     *
     * @param array|null $list 集合
     * @param array|null $listA 对象
     * @return array
     */
    public static function listAddToList(?array $list, ?array $listA): array
    {
        $newList = [];
        if (self::isNotEmpty($listA)) {
            $newList = array_merge($newList, $listA);
        }
        if (self::isNotEmpty($list)) {
            $newList = array_merge($newList, $list);
        }
        return $newList;
    }

    /**
     * 判断一个数组是否非空
     *
     * @param array|null $coll 要判断的数组
     * @return bool true：非空 false：空
     */
    public static function isNotEmpty(?array $coll): bool
    {
        return !self::isEmpty($coll);
    }

    /**
     * 几个元素生成一个集合
     *
     * @param mixed ...$paramArr 对象数组
     * @return array
     */
    public static function toList(...$paramArr): array
    {
        if (ArrayUtil::isEmpty($paramArr)) {
            return [];
        }
        return $paramArr;
    }

    /**
     * 将数组合并，其中一个数组中包含多个数组
     *
     * @param array|null $list 需要合并得数组
     * @param array|null $lists 需要合并得包含多个数组得数组
     * @return array
     */
    public static function listAddListsToNew(?array $list, ?array $lists): array
    {
        $newList = [];
        if (self::isNotEmpty($lists)) {
            foreach ($lists as $ts) {
                if (self::isNotEmpty($ts)) {
                    $newList = array_merge($newList, $ts);
                }
            }
        }
        if (self::isNotEmpty($list)) {
            $newList = array_merge($newList, $list);
        }
        return $newList;
    }

    /**
     * 字符串集合拼接字符串
     *
     * @param array|null $list 字符串集合
     * @param string $sep 分隔符
     * @return string
     */
    public static function strListToStr(?array $list, string $sep): string
    {
        if (self::isEmpty($list)) {
            return '';
        }
        return implode($sep, $list);
    }

    /**
     * 按照 batchSize 分割源集合，微批概念
     *
     * @param array $list 源集合
     * @param int $batchSize 分批大小
     * @return array 指定 batchSize 的全部 list
     */
    public static function split(array $list, int $batchSize): array
    {
        if (self::isEmpty($list) || $batchSize <= 0) {
            return [];
        }

        $total  = count($list);
        $n      = (int)ceil($total / $batchSize);
        $result = [];

        for ($i = 0; $i < $n; $i++) {
            $start    = $i * $batchSize;
            $end      = min(($i + 1) * $batchSize, $total);
            $result[] = array_slice($list, $start, $end - $start);
        }

        return $result;
    }

    /**
     * 根据指定的比较器获取集合中的最大值元素
     *
     * @param array $list 集合
     * @param callable $comparator 比较器函数
     * @return mixed|null
     */
    public static function maxBy(array $list, callable $comparator): mixed
    {
        if (self::isEmpty($list)) {
            return null;
        }

        $maxItem  = $list[0];
        $maxValue = $comparator($maxItem);

        foreach ($list as $item) {
            $currentValue = $comparator($item);
            if ($currentValue > $maxValue) {
                $maxValue = $currentValue;
                $maxItem  = $item;
            }
        }

        return $maxItem;
    }
}
