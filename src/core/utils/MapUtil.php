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
 * Map 工具类
 *
 *
 * @since 2023/5/18 9:46
 */
class MapUtil
{
    /**
     * 判断一个 Map 是否为空
     *
     * @param array|null $map 要判断的 Map
     * @return bool true：为空 false：非空
     */
    public static function isEmpty(?array $map): bool
    {
        return empty($map);
    }

    /**
     * 判断一个 Map 是否非空
     *
     * @param array|null $map 要判断的 Map
     * @return bool true：非空 false：空
     */
    public static function isNotEmpty(?array $map): bool
    {
        return !self::isEmpty($map);
    }

    /**
     * 如果 Map 是空，则返回默认值
     *
     * @param array|null $map Map
     * @param array $defaultMap 默认值
     * @return array 结果
     */
    public static function emptyDefault(?array $map, array $defaultMap): array
    {
        return self::isEmpty($map) ? $defaultMap : $map;
    }

    /**
     * 合并多个 map
     *
     * @param array ...$maps 需要合并的 map
     * @return array 合并后的结果
     */
    public static function mergeAll(?array ...$maps): array
    {
        $map = [];
        foreach ($maps as $m) {
            if (self::isNotEmpty($m)) {
                $map = array_merge($map, $m);
            }
        }
        return $map;
    }

    /**
     * 合并所有对象
     *
     * @param mixed ...$values
     * @return array
     */
    public static function mergeAllObj(...$values): array
    {
        $map = [];
        if (!empty($values)) {
            for ($i = 0; $i < count($values) - 1; $i += 2) {
                $key       = $values[$i];
                $value     = $values[$i + 1];
                $map[$key] = $value;
            }
        }
        return $map;
    }

    /**
     * 创建并放入一个键值对
     *
     * @param mixed $k 键
     * @param mixed $v 值
     * @return array
     */
    public static function newAndPut(mixed $k, mixed $v): array
    {
        return [$k => $v];
    }

    /**
     * 克隆一个 Map
     *
     * @param array $map 要克隆的 Map
     * @return array 克隆后的 Map
     */
    public static function clone(array $map): array
    {
        if (self::isEmpty($map)) {
            return [];
        }
        return $map;
    }
}
