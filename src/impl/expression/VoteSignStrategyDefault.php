<?php

namespace Yflow\impl\expression;

use Yflow\core\constant\FlowCons;
use Yflow\core\strategy\ExpressionStrategy;
use Yflow\core\strategy\VoteSignStrategy;
use Yflow\impl\helper\SpelHelper;

/**
 * 默认条件表达式 default@@${flag == 5 && flag > 4}
 *
 *
 */
class VoteSignStrategyDefault implements VoteSignStrategy
{
    /**
     * @var ExpressionStrategy
     */
    private ExpressionStrategy $expressionStrategy;

    public function getType(): string
    {
        return FlowCons::DEFAULT;
    }

    public function interceptStr(): string
    {
        return FlowCons::SPLIT_AT;
    }

    public function eval(string $expression, ?array $variable): bool
    {
        // 移除default@@前缀
        $expression = str_replace('default@@', '', $expression);
        return SpelHelper::processDollarExpression($expression, $variable ?? []);
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
