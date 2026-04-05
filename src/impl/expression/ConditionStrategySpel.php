<?php

namespace Yflow\impl\expression;

use Yflow\core\constant\FlowCons;
use Yflow\core\exception\FlowException;
use Yflow\core\strategy\ConditionStrategy;
use Yflow\core\strategy\ExpressionStrategy;
use Yflow\impl\helper\SpelHelper;

/**
 * spel条件表达式 spel@@#{@user.eval()}
 *
 *
 */
class ConditionStrategySpel implements ConditionStrategy
{
    private ExpressionStrategy $expressionStrategy;

    public function getType(): string
    {
        return FlowCons::SPEL;
    }

    public function interceptStr(): string
    {
        return FlowCons::SPLIT_AT;
    }

    /**
     * @throws FlowException
     */
    public function eval(string $expression, ?array $variable): bool
    {
        $result = SpelHelper::parseExpression($expression, $variable);
        return (bool)$result;
    }

    public function setExpression(ExpressionStrategy $expressionStrategy): void
    {
        $this->expressionStrategy = $expressionStrategy;
    }

    public static function getExpressionStrategyList(): array
    {
        return [];
    }
}
