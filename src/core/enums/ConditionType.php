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
 * 条件表达式类型
 *
 * @author xiarg
 * @since 2025/06/03 17:57:05
 */
enum ConditionType: string
{
    /**
     * 等于
     */
    case EQ = 'eq';

    /**
     * 大于等于
     */
    case GE = 'ge';

    /**
     * 大于
     */
    case GT = 'gt';

    /**
     * 小于等于
     */
    case LE = 'le';

    /**
     * 包含
     */
    case LIKE = 'like';

    /**
     * 小于
     */
    case LT = 'lt';

    /**
     * 不等于
     */
    case NE = 'ne';

    /**
     * 不包含
     */
    case NOT_LIKE = 'notLike';

    /**
     * 获取所有枚举值
     */
    public static function values(): array
    {
        return [
            self::EQ->value,
            self::GE->value,
            self::GT->value,
            self::LE->value,
            self::LIKE->value,
            self::LT->value,
            self::NE->value,
            self::NOT_LIKE->value,
        ];
    }

    /**
     * 根据值获取 key（描述）
     */
    public static function getKeyByValue(string $value): ?string
    {
        $map = [
            self::EQ->value       => '等于',
            self::GE->value       => '大于等于',
            self::GT->value       => '大于',
            self::LE->value       => '小于等于',
            self::LIKE->value     => '包含',
            self::LT->value       => '小于',
            self::NE->value       => '不等于',
            self::NOT_LIKE->value => '不包含',
        ];
        return $map[$value] ?? null;
    }

    /**
     * 根据 key（描述）获取值
     */
    public static function getValueByKey(string $key): ?string
    {
        $map = [
            '等于'     => self::EQ->value,
            '大于等于' => self::GE->value,
            '大于'     => self::GT->value,
            '小于等于' => self::LE->value,
            '包含'     => self::LIKE->value,
            '小于'     => self::LT->value,
            '不等于'   => self::NE->value,
            '不包含'   => self::NOT_LIKE->value,
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举数组
     */
    public static function getByKey(string $key): ?array
    {
        $map = [
            self::EQ->value       => ['key' => self::EQ->value, 'value' => '等于'],
            self::GE->value       => ['key' => self::GE->value, 'value' => '大于等于'],
            self::GT->value       => ['key' => self::GT->value, 'value' => '大于'],
            self::LE->value       => ['key' => self::LE->value, 'value' => '小于等于'],
            self::LIKE->value     => ['key' => self::LIKE->value, 'value' => '包含'],
            self::LT->value       => ['key' => self::LT->value, 'value' => '小于'],
            self::NE->value       => ['key' => self::NE->value, 'value' => '不等于'],
            self::NOT_LIKE->value => ['key' => self::NOT_LIKE->value, 'value' => '不包含'],
        ];
        return $map[$key] ?? null;
    }
}
