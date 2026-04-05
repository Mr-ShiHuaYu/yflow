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
 * 字符串工具类
 *
 *
 */
class StringUtils
{
    /**
     * 空字符串
     */
    public const NULLPTR = '';

    /**
     * 下划线
     */
    public const SEPARATOR = '_';

    /**
     * 字符串 0
     */
    public const ZERO = '0';

    /**
     * 获取参数不为空值
     *
     * @param mixed $value defaultValue 要判断的 value
     * @param mixed $defaultValue 默认值
     * @return mixed value 返回值
     */
    public static function nvl(mixed $value, mixed $defaultValue): mixed
    {
        return $value !== null ? $value : $defaultValue;
    }

    /**
     * 判断一个字符串是否为空串
     *
     * @param string|null $str String
     * @return bool true：为空 false：非空
     */
    public static function isEmpty(?string $str): bool
    {
        return $str === null || trim($str) === '';
    }

    /**
     * 判断一个字符串是否为非空串
     *
     * @param string|null $str String
     * @return bool true：非空串 false：空串
     */
    public static function isNotEmpty(?string $str): bool
    {
        return !self::isEmpty($str);
    }

    /**
     * 如果字符串是空，则返回默认值
     *
     * @param string|null $str 字符串
     * @param string|null $defaultStr 默认值
     * @return string|null 结果
     */
    public static function emptyDefault(?string $str, ?string $defaultStr): string|null
    {
        return self::isEmpty($str) ? $defaultStr : $str;
    }

    /**
     * 指定字符串数组中，是否包含空字符串
     *
     * @param array|null $args args
     * @return bool 是否为空
     */
    public static function hasEmpty(?array $args): bool
    {
        if (ArrayUtil::isEmpty($args)) {
            return true;
        }
        foreach ($args as $str) {
            if (self::isEmpty($str)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 是否存都不为 null 或空对象或空白符的对象
     *
     * @param array|null $args args
     * @return bool 全都不为空 = true;
     */
    public static function isAllNotEmpty(?array $args): bool
    {
        return !self::hasEmpty($args);
    }

    /**
     * 去空格
     *
     * @param string|null $str
     * @return string
     */
    public static function trim(?string $str): string
    {
        return $str === null ? '' : trim($str);
    }

    /**
     * 判断给定的数组 array 中是否包含给定的元素 value
     *
     * @param array|null $collection 给定的集合
     * @param array $array 给定的数组
     * @return bool 结果
     */
    public static function containsAny(?array $collection, array $array): bool
    {
        if (CollUtil::isEmpty($collection) || ArrayUtil::isEmpty($array)) {
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
     * 截取字符串
     *
     * @param string|null $str 字符串
     * @param int $start 开始
     * @return string 结果
     */
    public static function substring(?string $str, int $start): string
    {
        if ($str === null) {
            return self::NULLPTR;
        }

        if ($start < 0) {
            $start = strlen($str) + $start;
        }

        if ($start < 0) {
            $start = 0;
        }
        if ($start > strlen($str)) {
            return self::NULLPTR;
        }

        return substr($str, $start);
    }

    /**
     * 截取字符串
     *
     * @param string|null $str 字符串
     * @param int $start 开始
     * @param int $end 结束
     * @return string 结果
     */
    public static function substringEnd(?string $str, int $start, int $end): string
    {
        if ($str === null) {
            return self::NULLPTR;
        }

        if ($end < 0) {
            $end = strlen($str) + $end;
        }
        if ($start < 0) {
            $start = strlen($str) + $start;
        }

        if ($end > strlen($str)) {
            $end = strlen($str);
        }

        if ($start > $end) {
            return self::NULLPTR;
        }

        if ($start < 0) {
            $start = 0;
        }
        if ($end < 0) {
            $end = 0;
        }

        return substr($str, $start, $end - $start);
    }

    /**
     * 字符串转 set
     *
     * @param string|null $str 字符串
     * @param string $sep 分隔符
     * @return array set 集合
     */
    public static function str2Set(?string $str, string $sep): array
    {
        return array_unique(self::str2List($str, $sep));
    }

    /**
     * 字符串转 list
     *
     * @param string|null $str 字符串
     * @param string $sep 分隔符
     * @return array list 集合
     */
    public static function str2List(?string $str, string $sep): array
    {
        return self::str2ListDetail($str, $sep, true, true);
    }

    /**
     * 字符串转 list
     *
     * @param string|null $str 字符串
     * @param string $sep 分隔符
     * @param bool $filterBlank 过滤纯空白
     * @param bool $trim 去掉首尾空白
     * @return array list 集合
     */
    public static function str2ListDetail(?string $str, string $sep, bool $filterBlank, bool $trim): array
    {
        $list = [];
        if (self::isEmpty($str)) {
            return $list;
        }

        // 过滤空白字符串
        if ($filterBlank && self::isEmpty($str)) {
            return $list;
        }

        $split = explode($sep, $str);
        foreach ($split as $string) {
            if ($filterBlank && self::isEmpty($string)) {
                continue;
            }
            if ($trim) {
                $string = trim($string);
            }
            $list[] = $string;
        }

        return $list;
    }

    /**
     * 驼峰转下划线命名
     *
     * @param string|null $str
     * @return string|null
     */
    public static function toUnderScoreCase(?string $str): ?string
    {
        if ($str === null) {
            return null;
        }

        $sb = '';
        // 前置字符是否大写
        $preCharIsUpperCase = true;
        // 当前字符是否大写
        $curreCharIsUpperCase = true;
        // 下一字符是否大写
        $nexteCharIsUpperCase = true;

        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = $str[$i];
            if ($i > 0) {
                $preChar = $str[$i - 1];
                $preCharIsUpperCase = ord($preChar) >= 65 && ord($preChar) <= 90;
            } else {
                $preCharIsUpperCase = false;
            }

            $curreCharIsUpperCase = ord($c) >= 65 && ord($c) <= 90;

            if ($i < ($len - 1)) {
                $nextChar = $str[$i + 1];
                $nexteCharIsUpperCase = ord($nextChar) >= 65 && ord($nextChar) <= 90;
            }

            if ($preCharIsUpperCase && $curreCharIsUpperCase && !$nexteCharIsUpperCase) {
                $sb .= self::SEPARATOR;
            } elseif (($i !== 0 && !$preCharIsUpperCase) && $curreCharIsUpperCase) {
                $sb .= self::SEPARATOR;
            }
            $sb .= strtolower($c);
        }

        return $sb;
    }

    /**
     * 是否包含字符串
     *
     * @param string|null $str 验证字符串
     * @param array|null $strs 字符串组
     * @return bool 包含返回 true
     */
    public static function inStringIgnoreCase(?string $str, ?array $strs): bool
    {
        if ($str !== null && $strs !== null) {
            foreach ($strs as $s) {
                if (strcasecmp($str, trim($s)) === 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 将下划线大写方式命名的字符串转换为驼峰式。如果转换前的下划线大写方式命名的字符串为空，则返回空字符串。
     * 例如：HELLO_WORLD->HelloWorld
     *
     * @param string|null $name 转换前的下划线大写方式命名的字符串
     * @return string 转换后的驼峰式命名的字符串
     */
    public static function convertToCamelCase(?string $name): string
    {
        // 快速检查
        if ($name === null || $name === '') {
            // 没必要转换
            return '';
        } elseif (!str_contains($name, '_')) {
            // 不含下划线，仅将首字母大写
            return ucfirst($name);
        }

        // 用下划线将原始字符串分割
        $camels = explode('_', $name);
        $result = '';
        foreach ($camels as $camel) {
            // 跳过原始字符串中开头、结尾的下换线或双重下划线
            if ($camel === '') {
                continue;
            }
            // 首字母大写，其余小写
            $result .= ucfirst(strtolower($camel));
        }
        return $result;
    }

    /**
     * 驼峰式命名法
     * 例如：user_name->userName
     *
     * @param string|null $s
     * @return string|null
     */
    public static function toCamelCase(?string $s): ?string
    {
        if ($s === null) {
            return null;
        }
        if (!str_contains($s, '_')) {
            return $s;
        }
        $s         = strtolower($s);
        $sb        = '';
        $upperCase = false;
        $len       = strlen($s);

        for ($i = 0; $i < $len; $i++) {
            $c = $s[$i];

            if ($c === '_') {
                $upperCase = true;
            } elseif ($upperCase) {
                $sb        .= strtoupper($c);
                $upperCase = false;
            } else {
                $sb .= $c;
            }
        }

        return $sb;
    }

    /**
     * 数字左边补齐 0，使之达到指定长度。注意，如果数字转换为字符串后，长度大于 size，则只保留 最后 size 个字符。
     *
     * @param mixed $num 数字对象
     * @param int $size 字符串指定长度
     * @return string 返回数字的字符串格式，该字符串为指定长度。
     */
    public static function padLeft(mixed $num, int $size): string
    {
        return self::padLeftChar((string)$num, $size, '0');
    }

    /**
     * 字符串左补齐。如果原始字符串 s 长度大于 size，则只保留最后 size 个字符。
     *
     * @param string|null $s 原始字符串
     * @param int $size 字符串指定长度
     * @param string $c 用于补齐的字符
     * @return string 返回指定长度的字符串，由原字符串左补齐或截取得到。
     */
    public static function padLeftChar(?string $s, int $size, string $c): string
    {
        if ($s !== null) {
            $len = strlen($s);
            if ($len <= $size) {
                return str_repeat($c, $size - $len) . $s;
            } else {
                return substr($s, $len - $size, $len);
            }
        } else {
            return str_repeat($c, $size);
        }
    }

    /**
     * 字符串拼接
     *
     * @param array $arr
     * @param string $delimiter
     * @return string|null
     */
    public static function join(array $arr, string $delimiter): ?string
    {
        return self::joinWithDelimiter($arr, $delimiter);
    }

    /**
     * 自定义分隔符的拼接
     *
     * @param array|null $array 待拼接对象数组
     * @param string|null $delimiter 分隔符
     * @return string|null 拼接后的字符串
     */
    public static function joinWithDelimiter(?array $array, ?string $delimiter): ?string
    {
        if ($array === null) {
            return null;
        }
        return self::joinWithPosition($array, $delimiter, 0, count($array));
    }

    /**
     * 指定位置拼接
     *
     * @param array|null $array 待拼接对象数组
     * @param string|null $delimiter 分隔符
     * @param int $startIndex 起始位置
     * @param int $endIndex 终止位置
     * @return string 拼接后的字符串
     */
    public static function joinWithPosition(?array $array, ?string $delimiter, int $startIndex, int $endIndex): string
    {
        if ($array === null) {
            return '';
        } elseif ($endIndex - $startIndex <= 0) {
            return '';
        } else {
            $result = [];
            for ($i = $startIndex; $i < $endIndex; ++$i) {
                $result[] = (string)($array[$i] ?? '');
            }
            return implode($delimiter ?? '', $result);
        }
    }
}
