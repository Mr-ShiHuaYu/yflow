<?php

namespace Yflow\impl\expression;

use Yflow\core\exception\FlowException;
use Yflow\core\strategy\ExpressionStrategy;
use Yflow\core\strategy\HandlerStrategy;
use Yflow\core\strategy\HandlerStrategyDefaultTrait;
use Yflow\impl\helper\SpelHelper;

/**
 * 办理人表达式spel: @@spel@@|#{@user.evalVar()}
 *
 *
 */
class HandlerStrategySpel implements HandlerStrategy
{
    use HandlerStrategyDefaultTrait;

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
    public function preEval(string $expression, array $variable): mixed
    {
        $result = SpelHelper::parseExpression($expression, $variable);
        if (is_array($result)) {
            return $result;
        } elseif (is_scalar($result)) {
            return [$result];
        } elseif (is_object($result)) {
            // 尝试将对象转换为数组或字符串
            if (method_exists($result, 'toArray')) {
                return $result->toArray();
            } elseif (method_exists($result, 'jsonSerialize')) {
                return [$result->jsonSerialize()];
            } else {
                return [json_encode($result)];
            }
        }
        return [];
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
