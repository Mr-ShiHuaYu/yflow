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
 * 表单类型
 *
 *
 * @since 2025/6/25
 */
enum FormCustomEnum: string
{
    /**
     * 否
     */
    case N = 'N';

    /**
     * 是
     */
    case Y = 'Y';

    /**
     * 判断是否为是
     */
    public static function isYes(?string $value): bool
    {
        return $value !== null && $value === self::Y->value;
    }

    /**
     * 判断是否为否
     */
    public static function isNo(?string $value): bool
    {
        return $value !== null && $value === self::N->value;
    }

    /**
     * 根据值获取描述
     */
    public static function getValueByKey(string $key): ?string
    {
        $map = [
            self::N->value => '否',
            self::Y->value => '是',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举数组
     */
    public static function getByKey(string $key): ?array
    {
        $map = [
            self::N->value => ['key' => self::N->value, 'value' => '否'],
            self::Y->value => ['key' => self::Y->value, 'value' => '是'],
        ];
        return $map[$key] ?? null;
    }
}
