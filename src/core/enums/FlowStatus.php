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
 * 流程状态
 *
 *
 * @since 2023/3/31 12:16
 */
enum FlowStatus: string
{
    /**
     * 待提交
     */
    case TOBESUBMIT = '0';

    /**
     * 审批中
     */
    case APPROVAL = '1';

    /**
     * 审批通过
     */
    case PASS = '2';

    /**
     * 自动完成
     */
    case AUTO_PASS = '3';

    /**
     * 终止
     */
    case TERMINATE = '4';

    /**
     * 作废
     */
    case NULLIFY = '5';

    /**
     * 撤销
     */
    case CANCEL = '6';

    /**
     * 取回
     */
    case RETRIEVE = '7';

    /**
     * 已完成
     */
    case FINISHED = '8';

    /**
     * 已退回
     */
    case REJECT = '9';

    /**
     * 失效
     */
    case INVALID = '10';

    /**
     * 拿回
     */
    case TASK_BACK = '11';

    /**
     * 重启
     */
    case RE_START = '12';

    /**
     * 获取值对应的 key
     */
    public static function getKeyByValue(string $value): ?string
    {
        $map = [
            '待提交'   => self::TOBESUBMIT->value,
            '审批中'   => self::APPROVAL->value,
            '审批通过' => self::PASS->value,
            '自动完成' => self::AUTO_PASS->value,
            '终止'     => self::TERMINATE->value,
            '作废'     => self::NULLIFY->value,
            '撤销'     => self::CANCEL->value,
            '取回'     => self::RETRIEVE->value,
            '已完成'   => self::FINISHED->value,
            '已退回'   => self::REJECT->value,
            '失效'     => self::INVALID->value,
            '拿回'     => self::TASK_BACK->value,
            '重启'     => self::RE_START->value,
        ];
        return $map[$value] ?? null;
    }

    /**
     * 根据 key 获取值
     */
    public static function getValueByKey(string $key): ?string
    {
        $map = [
            self::TOBESUBMIT->value => '待提交',
            self::APPROVAL->value   => '审批中',
            self::PASS->value       => '审批通过',
            self::AUTO_PASS->value  => '自动完成',
            self::TERMINATE->value  => '终止',
            self::NULLIFY->value    => '作废',
            self::CANCEL->value     => '撤销',
            self::RETRIEVE->value   => '取回',
            self::FINISHED->value   => '已完成',
            self::REJECT->value     => '已退回',
            self::INVALID->value    => '失效',
            self::TASK_BACK->value  => '拿回',
            self::RE_START->value   => '重启',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举
     */
    public static function getByKey(string $key): ?array
    {
        $map = [
            self::TOBESUBMIT->value => ['key' => self::TOBESUBMIT->value, 'value' => '待提交'],
            self::APPROVAL->value   => ['key' => self::APPROVAL->value, 'value' => '审批中'],
            self::PASS->value       => ['key' => self::PASS->value, 'value' => '审批通过'],
            self::AUTO_PASS->value  => ['key' => self::AUTO_PASS->value, 'value' => '自动完成'],
            self::TERMINATE->value  => ['key' => self::TERMINATE->value, 'value' => '终止'],
            self::NULLIFY->value    => ['key' => self::NULLIFY->value, 'value' => '作废'],
            self::CANCEL->value     => ['key' => self::CANCEL->value, 'value' => '撤销'],
            self::RETRIEVE->value   => ['key' => self::RETRIEVE->value, 'value' => '取回'],
            self::FINISHED->value   => ['key' => self::FINISHED->value, 'value' => '已完成'],
            self::REJECT->value     => ['key' => self::REJECT->value, 'value' => '已退回'],
            self::INVALID->value    => ['key' => self::INVALID->value, 'value' => '失效'],
            self::TASK_BACK->value  => ['key' => self::TASK_BACK->value, 'value' => '拿回'],
            self::RE_START->value   => ['key' => self::RE_START->value, 'value' => '重启'],
        ];
        return $map[$key] ?? null;
    }

    /**
     * 判断是否结束节点
     */
    public static function isFinished(?string $key): bool
    {
        return $key !== null && $key === self::FINISHED->value;
    }
}
