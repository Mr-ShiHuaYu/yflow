<?php

namespace Yflow\core\strategy;

/**
 * 表达式策略类接口
 *
 *
 */
interface ExpressionStrategy
{
    /**
     * 获取策略类型
     *
     * @return string
     */
    public function getType(): string;

    /**
     * 当选择截取，并且希望拼接上某些字符串，在进行截取
     *
     * @return string
     */
    public function interceptStr(): string;

    /**
     * 执行表达式
     *
     * @param string $expression 表达式
     * @param null|array<string, mixed> $variable 流程变量
     * @return mixed 执行结果
     */
    public function eval(string $expression, ?array $variable): mixed;

    /**
     * 设置表达式
     *
     * @param ExpressionStrategy $expressionStrategy 表达式策略
     * @return void
     */
    public function setExpression(ExpressionStrategy $expressionStrategy): void;
}
