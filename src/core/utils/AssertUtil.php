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

use Yflow\core\exception\FlowException;


/**
 * Assert 工具类
 *
 *
 * @since 2023/3/30 14:05
 */
class AssertUtil
{
    private function __construct()
    {
    }

    /**
     * 判断对象是否为 null
     *
     * @param mixed $obj 对象
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function isNull(mixed $obj, string $errorMsg): void
    {
        if ($obj === null) {
            throw new FlowException($errorMsg);
        }
    }

    /**
     * 判断对象是否不为 null
     *
     * @param mixed $obj 对象
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function isNotNull(mixed $obj, string $errorMsg): void
    {
        if ($obj !== null) {
            throw new FlowException($errorMsg);
        }
    }

    /**
     * 为 true 不抛异常
     *
     * @param bool $obj 布尔值
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function isFalse(bool $obj, string $errorMsg): void
    {
        if (!$obj) {
            throw new FlowException($errorMsg);
        }
    }

    /**
     * 为 false 不抛异常
     *
     * @param bool $obj 布尔值
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function isTrue(bool $obj, string $errorMsg): void
    {
        if ($obj) {
            throw new FlowException($errorMsg);
        }
    }

    /**
     * 判断对象是否非空
     *
     * @param mixed $obj 对象
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function isNotEmpty(mixed $obj, string $errorMsg): void
    {
        if ($obj !== null) {
            if (is_string($obj)) {
                self::isTrue(StringUtils::isNotEmpty($obj), $errorMsg);
            } elseif (is_array($obj)) {
                self::isTrue(CollUtil::isNotEmpty($obj), $errorMsg);
            } else {
                throw new FlowException("Unsupported type: " . gettype($obj));
            }
        }
    }

    /**
     * 判断对象是否为空
     *
     * @param mixed $obj 对象
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function isEmpty(mixed $obj, string $errorMsg): void
    {
        if ($obj === null) {
            throw new FlowException($errorMsg);
        } elseif (is_string($obj)) {
            self::isTrue(StringUtils::isEmpty($obj), $errorMsg);
        } elseif (is_array($obj)) {
            self::isTrue(CollUtil::isEmpty($obj), $errorMsg);
        } else {
            throw new FlowException("Unsupported type: " . gettype($obj));
        }
    }

    /**
     * 判断集合是否包含元素
     *
     * @param array $a 集合
     * @param mixed $b 元素
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function contains(array $a, mixed $b, string $errorMsg): void
    {
        if (CollUtil::isNotEmpty($a)) {
            if (in_array($b, $a)) {
                throw new FlowException($errorMsg);
            }
        }
    }

    /**
     * 判断集合是否不包含元素
     *
     * @param array $a 集合
     * @param mixed $b 元素
     * @param string $errorMsg 错误消息
     * @throws FlowException
     */
    public static function notContains(array $a, mixed $b, string $errorMsg): void
    {
        if (CollUtil::isEmpty($a) || !in_array($b, $a)) {
            throw new FlowException($errorMsg);
        }
    }
}
