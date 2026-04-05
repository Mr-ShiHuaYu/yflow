<?php

namespace Yflow\core\strategy;

/**
 * 办理人表达式策略接口
 *
 * ,battcn
 */
interface HandlerStrategy extends ExpressionStrategy
{
    /**
     * 设置表达式
     *
     * @param ExpressionStrategy $expressionStrategy
     * @return void
     */
    public function setExpression(ExpressionStrategy $expressionStrategy): void;

    /**
     * 预计算表达式
     *
     * @param string $expression
     * @param array<string, mixed> $variable
     * @return mixed
     */
    public function preEval(string $expression, array $variable): mixed;

    /**
     * 执行表达式（默认实现）
     *
     * @param string $expression
     * @param null|array<string, mixed> $variable
     * @return array<string>|null
     */
    public function eval(string $expression, ?array $variable): ?array;

    /**
     * 表达式计算后处理
     *
     * @param mixed $o
     * @return array<string>|null
     */
    public function afterEval(mixed $o): ?array;


    /**
     * 获取表达式策略列表
     *
     * @return array<ExpressionStrategy>
     */
    public static function getExpressionStrategyList(): array;
}
