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

use Yflow\core\utils\StringUtils;

/**
 * 跳转类型
 *
 *
 * @since 2023/3/31 12:16
 */
enum SkipType: string
{
    /**
     * 审批通过
     */
    case PASS = 'PASS';

    /**
     * 退回
     */
    case REJECT = 'REJECT';

    /**
     * 无动作
     */
    case NONE = 'NONE';

    /**
     * 获取值对应的 key
     */
    public static function getKeyByValue(string $value): ?string
    {
        $map = [
            '审批通过' => self::PASS->value,
            '退回'     => self::REJECT->value,
            '无动作'   => self::NONE->value,
        ];
        return $map[$value] ?? null;
    }

    /**
     * 根据 key 获取值
     */
    public static function getValueByKey(string $key): ?string
    {
        $map = [
            self::PASS->value   => '审批通过',
            self::REJECT->value => '退回',
            self::NONE->value   => '无动作',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举
     */
    public static function getByKey(string $key): ?array
    {
        $map = [
            self::PASS->value   => ['key' => self::PASS->value, 'value' => '审批通过'],
            self::REJECT->value => ['key' => self::REJECT->value, 'value' => '退回'],
            self::NONE->value   => ['key' => self::NONE->value, 'value' => '无动作'],
        ];
        return $map[$key] ?? null;
    }

    /**
     * 判断是否通过类型
     */
    public static function isPass(?string $key): bool
    {
        return StringUtils::isNotEmpty($key) && $key === self::PASS->value;
    }

    /**
     * 判断是否退回类型
     */
    public static function isReject(?string $key): bool
    {
        return StringUtils::isNotEmpty($key) && $key === self::REJECT->value;
    }

    /**
     * 判断是否无动作类型
     */
    public static function isNone(?string $key): bool
    {
        return StringUtils::isNotEmpty($key) && $key === self::NONE->value;
    }
}
