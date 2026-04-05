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
 * 流程用户类型
 *
 * @author xiarg
 * @since 2024/5/10 16:04
 */
enum UserType: string
{
    /**
     * 待办任务的审批人权限
     */
    case APPROVAL = '1';

    /**
     * 待办任务的转办人权限
     */
    case TRANSFER = '2';

    /**
     * 待办任务的委托人权限
     */
    case DEPUTE = '3';

    /**
     * 获取值对应的 key
     */
    public static function getKeyByValue(string $value): ?string
    {
        $map = [
            '待办任务的审批人权限' => self::APPROVAL->value,
            '待办任务的转办人权限' => self::TRANSFER->value,
            '待办任务的委托人权限' => self::DEPUTE->value,
        ];
        return $map[$value] ?? null;
    }

    /**
     * 根据 key 获取值
     */
    public static function getValueByKey(string $key): ?string
    {
        $map = [
            self::APPROVAL->value => '待办任务的审批人权限',
            self::TRANSFER->value => '待办任务的转办人权限',
            self::DEPUTE->value   => '待办任务的委托人权限',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举
     */
    public static function getByKey(string $key): ?array
    {
        $map = [
            self::APPROVAL->value => ['key' => self::APPROVAL->value, 'value' => '待办任务的审批人权限'],
            self::TRANSFER->value => ['key' => self::TRANSFER->value, 'value' => '待办任务的转办人权限'],
            self::DEPUTE->value   => ['key' => self::DEPUTE->value, 'value' => '待办任务的委托人权限'],
        ];
        return $map[$key] ?? null;
    }
}
