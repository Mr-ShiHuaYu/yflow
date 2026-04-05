<?php

namespace Yflow\impl\expression;

use Yflow\core\exception\FlowException;
use Yflow\core\strategy\ExpressionStrategy;
use Yflow\core\strategy\ListenerStrategy;
use Yflow\impl\helper\SpelHelper;

/**
 * spel监听器表达式 #{@user.eval()}
 *
 *
 */
class ListenerStrategySpel implements ListenerStrategy
{
    private ExpressionStrategy $expressionStrategy;

    public function getType(): string
    {
        return "#";
    }

    public function interceptStr(): string
    {
        return "#";
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
