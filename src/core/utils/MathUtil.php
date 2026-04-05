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
 * 数字工具类
 *
 *
 */
class MathUtil
{
    public const ONE_HUNDRED = '100';
    public const ZERO        = '0';

    private function __construct()
    {
    }

    /**
     * 判断是否为数字
     *
     * @param string|null $str 字符串
     * @return bool
     */
    public static function isNumeric(?string $str): bool
    {
        if ($str === null || trim($str) === '') {
            return false;
        }
        return is_numeric(trim($str));
    }

    /**
     * 判断数字大小
     *
     * @param string $n1 字符串
     * @param string $n2 字符串
     * @return int -1: n1 < n2, 0: n1 == n2, 1: n1 > n2
     */
    public static function determineSize(string $n1, string $n2): int
    {
        $num1 = floatval($n1);
        $num2 = floatval($n2);

        if ($num1 < $num2) {
            return -1;
        } elseif ($num1 > $num2) {
            return 1;
        }
        return 0;
    }

    /**
     * 判断是否为 0
     *
     * @param string|null $str 字符串
     * @return bool
     */
    public static function isZero(?string $str): bool
    {
        if (StringUtils::isEmpty($str)) {
            return false;
        }
        $value = floatval(trim($str));
        return $value === 0.0;
    }

    /**
     * 判断字符串是否等于 100
     *
     * @param string|null $str 字符串
     * @return bool true：等于 100；false：不等于
     */
    public static function isHundred(?string $str): bool
    {
        if (StringUtils::isEmpty($str)) {
            return false;
        }
        $value = floatval(trim($str));
        return $value === 100.0;
    }

    /**
     * 判断字符串表示的数值是否在 0 到 100 之间（含边界）
     *
     * @param string|null $str 字符串
     * @return bool true：在 [0, 100] 范围内；false：不在范围内
     */
    public static function isBetweenZeroAndHundred(?string $str): bool
    {
        if (StringUtils::isEmpty($str)) {
            return false;
        }
        $value = floatval(trim($str));
        return $value >= 0.0 && $value <= 100.0;
    }
}
