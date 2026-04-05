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
 * Object 工具类
 *
 *
 * @since 2023/5/18 9:42
 */
class ObjectUtil
{
    /**
     * 判断一个对象是否为空
     *
     * @param mixed $object Object
     * @return bool true：为空 false：非空
     */
    public static function isNull(mixed $object): bool
    {
        return $object === null;
    }

    /**
     * 判断一个对象是否非空
     *
     * @param mixed $object Object
     * @return bool true：非空 false：空
     */
    public static function isNotNull(mixed $object): bool
    {
        return !self::isNull($object);
    }

    /**
     * 判断字符串是否为 true
     *
     * @param string|null $str
     * @return bool
     */
    public static function isStrTrue(?string $str): bool
    {
        return StringUtils::isNotEmpty($str) && $str === 'true';
    }

    /**
     * 如果被检查对象为 null，返回默认值 defaultValue；否则直接返回
     *
     * @param mixed $source 被检查对象
     * @param mixed $defaultValue 默认值
     * @return mixed
     */
    public static function defaultNull(mixed $source, mixed $defaultValue): mixed
    {
        if (self::isNull($source)) {
            return $defaultValue;
        }
        return $source;
    }

    /**
     * 如果被检查对象为 null，返回默认值（由 callback 提供）；否则直接返回
     *
     * @param mixed $source 被检查对象
     * @param callable $defaultValueSupplier 默认值提供者
     * @return mixed
     */
    public static function defaultIfNull(mixed $source, callable $defaultValueSupplier): mixed
    {
        if (self::isNull($source)) {
            return $defaultValueSupplier();
        }
        return $source;
    }
}
