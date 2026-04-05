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

namespace Yflow\core\utils;

use Exception;
use Yflow\core\condition\ConditionStrategyEq;
use Yflow\core\condition\ConditionStrategyGe;
use Yflow\core\condition\ConditionStrategyGt;
use Yflow\core\condition\ConditionStrategyLe;
use Yflow\core\condition\ConditionStrategyLike;
use Yflow\core\condition\ConditionStrategyLt;
use Yflow\core\condition\ConditionStrategyNe;
use Yflow\core\condition\ConditionStrategyNotLike;
use Yflow\core\constant\ExceptionCons;
use Yflow\core\dto\FlowParams;
use Yflow\core\FlowEngine;
use Yflow\core\handler\DefaultHandlerStrategy;
use Yflow\core\strategy\ConditionStrategy;
use Yflow\core\strategy\ExpressionStrategy;
use Yflow\core\strategy\HandlerStrategy;
use Yflow\core\strategy\ListenerStrategy;
use Yflow\core\strategy\VoteSignStrategy;
use Yflow\impl\orm\laravel\FlowTaskModel;


/**
 * 表达式工具类
 *
 *
 */
class ExpressionUtil
{
    /**
     * 条件表达式策略实现类集合
     */
    private static array $conditionStrategyList = [];

    /**
     * 办理人表达式策略实现类集合
     */
    private static array $handlerStrategyList = [];

    /**
     * 监听器表达式策略实现类集合
     */
    private static array $listenerStrategyList = [];

    /**
     * 票签表达式策略实现类集合
     */
    private static array $voteSignStrategyList = [];

    /**
     * 静态初始化
     */
    public static function init(): void
    {
        // 注册条件表达式
        self::setExpression(new ConditionStrategyEq());
        self::setExpression(new ConditionStrategyGe());
        self::setExpression(new ConditionStrategyGt());
        self::setExpression(new ConditionStrategyLe());
        self::setExpression(new ConditionStrategyLike());
        self::setExpression(new ConditionStrategyLt());
        self::setExpression(new ConditionStrategyNe());
        self::setExpression(new ConditionStrategyNotLike());

        // 注册办理人表达式
        self::setExpression(new DefaultHandlerStrategy());
    }

    /**
     * 设置表达式策略
     *
     * @param ExpressionStrategy $strategy 策略对象
     * @return void
     */
    public static function setExpression(ExpressionStrategy $strategy): void
    {
        $strategy->setExpression($strategy);

        // 将策略添加到对应的策略列表中
        if ($strategy instanceof ConditionStrategy) {
            self::$conditionStrategyList[] = $strategy;
        } elseif ($strategy instanceof HandlerStrategy) {
            self::$handlerStrategyList[] = $strategy;
        } elseif ($strategy instanceof ListenerStrategy) {
            self::$listenerStrategyList[] = $strategy;
        } elseif ($strategy instanceof VoteSignStrategy) {
            self::$voteSignStrategyList[] = $strategy;
        }
    }

    /**
     * 获取条件表达式策略列表
     *
     * @return array<ExpressionStrategy>
     */
    public static function getConditionStrategyList(): array
    {
        return self::$conditionStrategyList;
    }

    /**
     * 获取办理人表达式策略列表
     *
     * @return array<ExpressionStrategy>
     */
    public static function getHandlerStrategyList(): array
    {
        return self::$handlerStrategyList;
    }

    /**
     * 获取监听器表达式策略列表
     *
     * @return array<ExpressionStrategy>
     */
    public static function getListenerStrategyList(): array
    {
        return self::$listenerStrategyList;
    }

    /**
     * 获取票签表达式策略列表
     *
     * @return array<ExpressionStrategy>
     */
    public static function getVoteSignStrategyList(): array
    {
        return self::$voteSignStrategyList;
    }

    /**
     * 获取表达式对应的值
     *
     * @param array $strategyList 表达式策略列表
     * @param string $expression 变量表达式
     * @param array|null $variable 流程变量
     * @param string $errMsg 错误消息
     * @return mixed 执行结果
     * @throws Exception
     */
    private static function getValue(array $strategyList, string $expression, ?array $variable, string $errMsg): mixed
    {
        if (!empty($expression)) {
            // 倒叙遍历，优先匹配最后注入的策略实现类
            for ($i = count($strategyList) - 1; $i >= 0; $i--) {
                $strategy = $strategyList[$i];
                if ($strategy === null) {
                    throw new Exception($errMsg);
                }
                if (str_starts_with($expression, $strategy->getType())) {
                    if (!empty($strategy->interceptStr())) {
                        $expression = str_replace($strategy->getType() . $strategy->interceptStr(), '', $expression);
                    }
                    return $strategy->eval($expression, $variable ?? []);
                }
            }
        }
        return null;
    }

    /**
     * 条件表达式替换
     *
     * @param string $expression 条件表达式，比如"eq@@flag|4" ，或者自定义策略
     * @param array|null $variable 变量
     * @return bool
     * @throws Exception
     */
    public static function evalCondition(string $expression, ?array $variable): bool
    {
        return self::getValue(self::getConditionStrategyList(), $expression, $variable, ExceptionCons::NULL_CONDITION_STRATEGY) === true;
    }

    /**
     * 办理人表达式替换
     *
     * @param array<FlowTaskModel> $addTasks 任务列表
     * @param FlowParams $flowParams 流程变量
     * @throws Exception
     */
    public static function evalVariable(array $addTasks, FlowParams $flowParams): void
    {
        $variable = $flowParams->getVariable();
        foreach ($addTasks as $addTask) {
            $permissions = [];
            foreach ($addTask->getPermissionList() as $s) {
                $result = self::evalVariableByExp($s, $variable);
                if (!empty($result)) {
                    $permissions = array_merge($permissions, $result);
                } else {
                    $permissions[] = $s;
                }
            }
            $permissions = array_unique($permissions);

            // 转换办理人，比如设计器中预设了能办理的人，如果其中包含角色或者部门id等，可以通过此接口进行转换成用户id
            $permissionHandler = FlowEngine::permissionHandler();
            if ($permissionHandler !== null) {
                $permissions = $permissionHandler->convertPermissions($permissions);
            }
            // 自定义下个任务的处理人 下个任务处理人配置类型 和 执行的下个任务的办理人
            $permissions = self::nextHandle($flowParams->isNextHandlerAppend(), $flowParams->getNextHandler(), $permissions);


            $addTask->setPermissionList($permissions);

        }
    }

    /**
     * 办理人表达式替换
     *
     * @param string $expression 表达式，比如"${flag}或者# { &#064;user.notify(#listenerVariable) } " ，或者自定义策略
     * @param array|null $variable 流程变量
     * @return array
     * @throws Exception
     */
    public static function evalVariableByExp(string $expression, ?array $variable): array
    {
        return self::getValue(self::getHandlerStrategyList(), $expression, $variable, ExceptionCons::NULL_VARIABLE_STRATEGY) ?? [];
    }

    /**
     * 监听器表达式替换
     *
     * @param string $expression 条件表达式，比如"# { &#064;user.notify(#listenerVariable) } " ，或者自定义策略
     * @param array|null $variable 变量
     * @return bool
     * @throws Exception
     */
    public static function evalListener(string $expression, ?array $variable): bool
    {
        return self::getValue(self::getListenerStrategyList(), $expression, $variable, ExceptionCons::NULL_LISTENER_STRATEGY) === true;
    }

    /**
     * 票签表达式替换
     *
     * @param string $expression 表达式，比如"${flag}或者# { &#064;user.notify(#listenerVariable) } " ，或者自定义策略
     * @param array|null $variable 流程变量
     * @return bool
     * @throws Exception
     */
    public static function evalVoteSign(string $expression, ?array $variable): bool
    {
        return self::getValue(self::getVoteSignStrategyList(), $expression, $variable, ExceptionCons::NULL_VOTESIGN_STRATEGY) === true;
    }

    /**
     * 处理下个任务的处理人
     *
     * @param bool $nextHandlerAppend 下个任务处理人配置类型
     * @param array $nextHandler 下个任务的处理人
     * @param array $permissions 节点配置的原下个任务的处理人
     * @return array
     */
    private static function nextHandle(bool $nextHandlerAppend, array $nextHandler, array $permissions): array
    {
        if (empty($nextHandler)) {
            return $permissions;
        }
        if ($nextHandlerAppend) {
            $permissions = array_merge($permissions, $nextHandler);
        } else {
            $permissions = $nextHandler;
        }
        return $permissions;
    }
}
