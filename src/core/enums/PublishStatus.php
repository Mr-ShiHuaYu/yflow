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
 * 发布状态
 *
 *
 * @since 2023/3/31 12:16
 */
enum PublishStatus: int
{
    /**
     * 已失效
     */
    case EXPIRED = 9;

    /**
     * 未发布
     */
    case UNPUBLISHED = 0;

    /**
     * 已发布
     */
    case PUBLISHED = 1;

    /**
     * 获取值对应的 key
     */
    public static function getKeyByValue(string $value): ?int
    {
        $map = [
            '已失效' => self::EXPIRED->value,
            '未发布' => self::UNPUBLISHED->value,
            '已发布' => self::PUBLISHED->value,
        ];
        return $map[$value] ?? null;
    }

    /**
     * 根据 key 获取值
     */
    public static function getValueByKey(int $key): ?string
    {
        $map = [
            self::EXPIRED->value     => '已失效',
            self::UNPUBLISHED->value => '未发布',
            self::PUBLISHED->value   => '已发布',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举
     */
    public static function getByKey(int $key): ?array
    {
        $map = [
            self::EXPIRED->value     => ['key' => self::EXPIRED->value, 'value' => '已失效'],
            self::UNPUBLISHED->value => ['key' => self::UNPUBLISHED->value, 'value' => '未发布'],
            self::PUBLISHED->value   => ['key' => self::PUBLISHED->value, 'value' => '已发布'],
        ];
        return $map[$key] ?? null;
    }
}
