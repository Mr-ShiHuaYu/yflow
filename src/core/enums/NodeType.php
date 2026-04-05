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

namespace Yflow\core\enums;

/**
 * 节点类型
 *
 *
 * @since 2023/3/31 12:16
 */
enum NodeType: int
{
    /**
     * 开始节点
     */
    case START = 0;

    /**
     * 中间节点
     */
    case BETWEEN = 1;

    /**
     * 结束节点
     */
    case END = 2;

    /**
     * 互斥网关
     */
    case SERIAL = 3;

    /**
     * 并行网关
     */
    case PARALLEL = 4;

    /**
     * 包容网关
     */
    case INCLUSIVE = 5;

    /**
     * 获取值对应的 key
     */
    public static function getKeyByValue(string $value): ?int
    {
        $map = [
            'start'     => self::START->value,
            'between'   => self::BETWEEN->value,
            'end'       => self::END->value,
            'serial'    => self::SERIAL->value,
            'parallel'  => self::PARALLEL->value,
            'inclusive' => self::INCLUSIVE->value,
        ];
        return $map[$value] ?? null;
    }

    /**
     * 根据 key 获取值
     */
    public static function getValueByKey(?int $key): ?string
    {
        $map = [
            self::START->value     => 'start',
            self::BETWEEN->value   => 'between',
            self::END->value       => 'end',
            self::SERIAL->value    => 'serial',
            self::PARALLEL->value  => 'parallel',
            self::INCLUSIVE->value => 'inclusive',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举
     */
    public static function getByKey(?int $key): ?array
    {
        $map = [
            self::START->value     => ['key' => self::START->value, 'value' => 'start'],
            self::BETWEEN->value   => ['key' => self::BETWEEN->value, 'value' => 'between'],
            self::END->value       => ['key' => self::END->value, 'value' => 'end'],
            self::SERIAL->value    => ['key' => self::SERIAL->value, 'value' => 'serial'],
            self::PARALLEL->value  => ['key' => self::PARALLEL->value, 'value' => 'parallel'],
            self::INCLUSIVE->value => ['key' => self::INCLUSIVE->value, 'value' => 'inclusive'],
        ];
        return $map[$key] ?? null;
    }

    /**
     * 判断是否开始节点
     */
    public static function isStart(?int $key): bool
    {
        return $key !== null && $key === self::START->value;
    }

    /**
     * 判断是否中间节点
     */
    public static function isBetween(?int $key): bool
    {
        return $key === self::BETWEEN->value;
    }

    /**
     * 判断是否结束节点
     */
    public static function isEnd(?int $key): bool
    {
        return $key === self::END->value;
    }

    /**
     * 判断是否网关节点
     */
    public static function isGateWay(?int $key): bool
    {
        return ($key === self::SERIAL->value || $key === self::PARALLEL->value || $key === self::INCLUSIVE->value);
    }

    /**
     * 判断是否互斥网关节点
     */
    public static function isGateWaySerial(?int $key): bool
    {
        return $key === self::SERIAL->value;
    }

    /**
     * 判断是否并行网关节点
     */
    public static function isGateWayParallel(?int $key): bool
    {
        return $key === self::PARALLEL->value;
    }

    /**
     * 判断是否包容网关节点
     */
    public static function isGateWayInclusive(?int $key): bool
    {
        return $key === self::INCLUSIVE->value;
    }
}
