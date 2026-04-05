<?php

namespace Yflow\core\strategy;

/**
 * 监听器表达式策略接口
 *
 * ,battcn
 */
interface ListenerStrategy extends ExpressionStrategy
{
    /**
     * 设置表达式
     *
     * @param ExpressionStrategy $expressionStrategy
     * @return void
     */
    public function setExpression(ExpressionStrategy $expressionStrategy): void;

    /**
     * 获取表达式策略列表
     *
     * @return array<ExpressionStrategy>
     */
    public static function getExpressionStrategyList(): array;
}
