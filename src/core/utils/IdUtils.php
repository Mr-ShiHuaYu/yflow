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

use Yflow\core\keygen\KenGen;

/**
 * 唯一 ID 生成工具类（简化版）
 *
 * 注意：完整版本需要依赖 KenGen、SnowFlakeId 等类
 * 这里使用 PHP 内置的雪花算法或随机数生成
 *
 *
 * @since 2023/5/17 23:08
 */
class IdUtils implements KenGen
{
    /**
     * 内置 ID 算法实例
     */
    private static SnowflakeIdGenerator|null $instance = null;

    /**
     * ORM 框架配置的原生 ID 算法
     */
    private static mixed $instanceNative = null;

    /**
     * 生成下一个 ID（字符串）
     *
     * @return string
     */
    public static function nextIdStr(): string
    {
        return (string)self::nextId();
    }

    /**
     * 生成下一个 ID（数字）
     *
     * @return int
     */
    public static function nextId(): int
    {
        return self::nextIdWithParams();
    }

    /**
     * 生成下一个 ID（带参数）
     *
     * @param int $workerId 工作节点 ID
     * @param int $datacenterId 数据中心 ID
     * @return int
     */
    public static function nextIdWithParams(int $workerId = 1, int $datacenterId = 1): int
    {
        if (self::$instance === null) {
            // 简化实现：使用时间戳 + 随机数生成唯一 ID
            // 实际项目中应该实现完整的雪花算法
            self::$instance = new SnowflakeIdGenerator($workerId, $datacenterId);
        }

        return self::$instance->nextId();
    }

    /**
     * 设置原生 ID 算法实例
     *
     * @param mixed $instanceNative 原生 ID 生成器
     * @return void
     */
    public static function setInstanceNative(mixed $instanceNative): void
    {
        self::$instanceNative = $instanceNative;
    }
}
