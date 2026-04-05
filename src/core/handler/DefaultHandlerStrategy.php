<?php

namespace Yflow\core\handler;

use Yflow\core\strategy\ExpressionStrategyListTrait;
use Yflow\core\strategy\HandlerStrategy;
use Yflow\core\strategy\HandlerStrategyDefaultTrait;

/**
 * 默认办理人表达式策略： @@default@@|${flag}
 *
 *
 */
class DefaultHandlerStrategy implements HandlerStrategy
{
    use ExpressionStrategyListTrait, HandlerStrategyDefaultTrait;

    /**
     * 获取类型
     *
     * @return string
     */
    public function getType(): string
    {
        return '$';
    }

    /**
     * 预计算表达式
     *
     * @param string $expression
     * @param array<string, mixed> $variable
     * @return mixed
     */
    public function preEval(string $expression, array $variable): mixed
    {
        $result = str_replace(['${', '}'], '', $expression);
        return $variable[$result] ?? [];
    }

}
