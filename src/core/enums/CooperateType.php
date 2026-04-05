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

use Yflow\core\constant\FlowCons;
use Yflow\core\utils\MathUtil;
use Yflow\core\utils\StringUtils;

/**
 * 协作类型
 * APPROVAL-无：无其他协作方式
 * TRANSFER-转办：任务转给其他人办理
 * DEPUTE-委派：求助其他人审批，然后参照他的意见决定是否审批通过
 * COUNTERSIGN-会签：和其他人一起审批通过，才算通过
 * VOTE-票签：和部分人一起审批，达到一定通过率，才算通过
 * ADD_SIGNATURE-加签：办理中途，希望其他人一起参与办理
 * REDUCTION_SIGNATURE-减签：办理中途，希望某些人不参与办理
 *
 * @author xiarg
 * @since 2024/5/10 16:04
 */
enum CooperateType: int
{
    /**
     * 无
     */
    case APPROVAL = 1;

    /**
     * 转办
     */
    case TRANSFER = 2;

    /**
     * 委派
     */
    case DEPUTE = 3;

    /**
     * 会签
     */
    case COUNTERSIGN = 4;

    /**
     * 票签
     */
    case VOTE = 5;

    /**
     * 加签
     */
    case ADD_SIGNATURE = 6;

    /**
     * 减签
     */
    case REDUCTION_SIGNATURE = 7;

    /**
     * 票签中的固定通过人数策略前缀
     */
    public const PASS_COUNT = 'passCount';

    /**
     * 票签中的固定驳回人数策略前缀
     */
    public const REJECT_COUNT = 'rejectCount';

    /**
     * 顺签
     */
    public const SEQUENCE = 'sequence';

    /**
     * 获取值对应的 key
     */
    public static function getKeyByValue(string $value): ?int
    {
        $map = [
            '无'   => self::APPROVAL->value,
            '转办' => self::TRANSFER->value,
            '委派' => self::DEPUTE->value,
            '会签' => self::COUNTERSIGN->value,
            '票签' => self::VOTE->value,
            '加签' => self::ADD_SIGNATURE->value,
            '减签' => self::REDUCTION_SIGNATURE->value,
        ];
        return $map[$value] ?? null;
    }

    /**
     * 根据 key 获取值
     */
    public static function getValueByKey(int $key): ?string
    {
        $map = [
            self::APPROVAL->value            => '无',
            self::TRANSFER->value            => '转办',
            self::DEPUTE->value              => '委派',
            self::COUNTERSIGN->value         => '会签',
            self::VOTE->value                => '票签',
            self::ADD_SIGNATURE->value       => '加签',
            self::REDUCTION_SIGNATURE->value => '减签',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举
     */
    public static function getByKey(int $key): ?array
    {
        $map = [
            self::APPROVAL->value            => ['key' => self::APPROVAL->value, 'value' => '无'],
            self::TRANSFER->value            => ['key' => self::TRANSFER->value, 'value' => '转办'],
            self::DEPUTE->value              => ['key' => self::DEPUTE->value, 'value' => '委派'],
            self::COUNTERSIGN->value         => ['key' => self::COUNTERSIGN->value, 'value' => '会签'],
            self::VOTE->value                => ['key' => self::VOTE->value, 'value' => '票签'],
            self::ADD_SIGNATURE->value       => ['key' => self::ADD_SIGNATURE->value, 'value' => '加签'],
            self::REDUCTION_SIGNATURE->value => ['key' => self::REDUCTION_SIGNATURE->value, 'value' => '减签'],
        ];
        return $map[$key] ?? null;
    }

    /**
     * 判断是否为或签
     */
    public static function isOrSign(?string $ratio): bool
    {
        return MathUtil::isZero($ratio);
    }

    /**
     * 判断是否是会签
     */
    public static function isCountersign(?string $ratio): bool
    {
        return MathUtil::isHundred($ratio);
    }

    /**
     * 判断是否是票签中通过率策略
     */
    public static function isVoteSignPassRatio(?string $ratio): bool
    {
        return MathUtil::isBetweenZeroAndHundred($ratio);
    }

    /**
     * 判断是否是票签中的固定通过人数策略
     */
    public static function isVoteSignPassCount(?string $passCount): bool
    {
        return StringUtils::isNotEmpty($passCount) && str_starts_with($passCount, self::PASS_COUNT);
    }

    /**
     * 判断是否是票签中的固定驳回人数策略
     */
    public static function isVoteSignRejectCount(?string $rejectCount): bool
    {
        return StringUtils::isNotEmpty($rejectCount) && str_starts_with($rejectCount, self::REJECT_COUNT);
    }

    /**
     * 判断是否是票签中的默认表达式策略
     */
    public static function isVoteSignDefault(?string $expression): bool
    {
        return StringUtils::isNotEmpty($expression) && str_starts_with($expression, FlowCons::DEFAULT);
    }

    /**
     * 判断是否是票签中的 spel 表达式策略
     */
    public static function isVoteSignRejectSpel(?string $expression): bool
    {
        return StringUtils::isNotEmpty($expression) && str_starts_with($expression, FlowCons::SPEL);
    }

    /**
     * 判断是否是顺签
     */
    public static function isSequenceSign(?string $expression): bool
    {
        return StringUtils::isNotEmpty($expression) && str_ends_with($expression, FlowCons::SPLIT_AT . self::SEQUENCE);
    }

    /**
     * 移除顺签标识
     */
    public static function removeSequence(?string $expression): ?string
    {
        if (self::isSequenceSign($expression)) {
            return substr($expression, 0, strrpos($expression, FlowCons::SPLIT_AT . self::SEQUENCE));
        }
        return $expression;
    }
}
