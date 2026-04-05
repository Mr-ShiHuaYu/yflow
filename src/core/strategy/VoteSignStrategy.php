<?php

namespace Yflow\core\strategy;

/**
 * 票签表达式接口
 *
 *
 */
interface VoteSignStrategy extends ExpressionStrategy
{
    /**
     * 设置表达式
     *
     * @param ExpressionStrategy $expressionStrategy
     * @return void
     */
    public function setExpression(ExpressionStrategy $expressionStrategy): void;

    /**
     * 拦截字符串
     *
     * @return string
     */
    public function interceptStr(): string;

    /**
     * 获取表达式策略列表
     *
     * @return array<ExpressionStrategy>
     */
    public static function getExpressionStrategyList(): array;
}
