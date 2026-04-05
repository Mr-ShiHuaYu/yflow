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

namespace Yflow\core\condition;

use Yflow\core\constant\ExceptionCons;
use Yflow\core\constant\FlowCons;
use Yflow\core\exception\FlowException;
use Yflow\core\strategy\ConditionStrategy;
use Yflow\core\strategy\ExpressionStrategy;
use Yflow\core\strategy\ExpressionStrategyListTrait;
use Yflow\core\utils\AssertUtil;

/**
 * AbstractConditionStrategy - 条件表达式抽象类，复用部分代码
 *
 *
 */
abstract class AbstractConditionStrategy implements ConditionStrategy
{
    use ExpressionStrategyListTrait;

    /**
     * 拦截字符串
     *
     * @return string
     */
    public function interceptStr(): string
    {
        return FlowCons::SPLIT_AT;
    }

    /**
     * 获取表达式策略列表（静态方法，用于 ExpressionUtil 调用）
     * @return array<ExpressionStrategy>
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

    /**
     * 执行表达式前置方法 合法性校验
     *
     * @param string $name 变量名称：flag
     * @param array|null $variable 流程变量
     * @return void
     * @throws FlowException
     */
    public function preEval(string $name, ?array $variable): void
    {
        AssertUtil::isEmpty($variable, ExceptionCons::NULL_CONDITION_VALUE);
        $o = $variable[$name] ?? null;
        AssertUtil::isNull($o, ExceptionCons::NULL_CONDITION_VALUE);
    }

    /**
     * 执行表达式
     *
     * @param string $expression 表达式：flag@@5
     *                   在{@link \Yflow\core\utils\ExpressionUtil::evalCondition}中格式为，比如：eq@@flag|5，
     *                   截取前缀进入此方法后为：flag|5
     * @param array|null $variable 流程变量
     * @return bool|null 执行结果
     * @throws FlowException
     */
    public function eval(string $expression, ?array $variable): ?bool
    {
        $split = explode(FlowCons::SPLIT_VERTICAL, $expression);
        $this->preEval(trim($split[0]), $variable);
        $variableValue = strval($variable[trim($split[0])] ?? '');
        return $this->afterEval(trim($split[1] ?? ''), $variableValue);
    }

    /**
     * 执行表达式后置方法
     *
     * @param string $value 表达式最后一个参数，比如：eq@@flag|5 的 [5]
     * @param string $variableValue 流程变量值
     * @return bool|null 执行结果
     */
    abstract public function afterEval(string $value, string $variableValue): ?bool;
}
