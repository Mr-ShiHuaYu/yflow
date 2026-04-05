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

namespace Yflow\core\strategy;

/**
 * 表达式策略列表 Trait
 *
 * 用于管理策略接口的静态策略列表
 * Java 中接口可以定义静态字段（不是真正的常量），在运行时修改
 * PHP 接口常量不能修改，因此使用 trait 提供静态列表
 *
 *
 */
trait ExpressionStrategyListTrait
{
    /**
     * 表达式策略列表
     * @var array<ExpressionStrategy>
     */
    protected static array $expressionStrategyList = [];

    /**
     * 获取表达式策略列表（静态方法，用于 ExpressionUtil 调用）
     * @return array
     */
    public static function getExpressionStrategyList(): array
    {
        return static::$expressionStrategyList;
    }

    /**
     * 设置表达式
     *
     * @param ExpressionStrategy $expressionStrategy
     * @return void
     */
    public function setExpression(ExpressionStrategy $expressionStrategy): void
    {
        static::$expressionStrategyList[] = $expressionStrategy;
    }
}
