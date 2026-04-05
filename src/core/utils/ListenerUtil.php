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
use Yflow\core\constant\FlowCons;
use Yflow\core\enums\NodeType;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;
use Yflow\core\listener\ValueHolder;

/**
 * 监听器工具类
 *
 *
 */
class ListenerUtil
{

    private function __construct()
    {

    }

    /**
     * 执行完成监听器和下一节点的开始监听器
     *
     * @param ListenerVariable $listenerVariable 监听器变量
     * @return void
     * @throws Exception
     */
    public static function endCreateListener(ListenerVariable $listenerVariable): void
    {
        // 执行任务完成监听器
        self::executeListener($listenerVariable, Listener::LISTENER_FINISH);

        // 执行任务创建监听器
        $tasks = $listenerVariable->getNextTasks();
        foreach ($listenerVariable->getNextNodes() as $node) {
            if (!NodeType::isEnd($node->getNodeType())) {
                $nextTask = StreamUtils::filterOne($tasks, fn($task) => $task->getNodeCode() === $node->getNodeCode());
                $listenerVariable->setNode($node)
                    ->setNextNodes(null)
                    ->setTask($nextTask)
                    ->setNextTasks(null);
                self::executeListener($listenerVariable, Listener::LISTENER_CREATE);
            }
        }
    }

    /**
     * 执行监听器
     *
     * @param ListenerVariable $listenerVariable 监听器变量
     * @param string $type 监听器类型
     * @return void
     * @throws Exception
     */
    public static function executeListener(ListenerVariable $listenerVariable, string $type): void
    {
        // 1.先执行节点监听器,也就是在流程定义json文件中 nodeList 中的设置的监听器,listenerPath和listenerType,不需要注入,通过反射类名获取对象
        $listenerType = $listenerVariable->getNode()->getListenerType();
        self::execute($listenerVariable, $type, $listenerVariable->getNode()->getListenerPath(), $listenerType);
        // 2.再执行流程定义的监听器,也就是在流程定义json文件中最外层设置的监听器,不需要注入,通过反射类名获取对象
        $definition = $listenerVariable->getDefinition();
        self::execute($listenerVariable, $type, $definition->getListenerPath(), $definition->getListenerType());
        // 3.再执行全局监听器,需要注入,通过容器中获取
        $globalListener = FlowEngine::globalListener();
        if (ObjectUtil::isNotNull($globalListener)) {
            $globalListener->notify($type, $listenerVariable);
        }
    }

    /**
     * 执行具体的监听器
     *
     * @param ListenerVariable $listenerVariable 监听器变量
     * @param string|null $type 监听器类型
     * @param string|null $listenerPaths 监听器路径
     * @param string|null $listenerTypes 监听器类型
     * @return void
     * @throws Exception
     */
    public static function execute(ListenerVariable $listenerVariable, ?string $type, ?string $listenerPaths, ?string $listenerTypes): void
    {
        if (StringUtils::isNotEmpty($listenerTypes)) {
            $listenerTypeArr = explode(',', $listenerTypes);
            foreach ($listenerTypeArr as $i => $listenerType) {
                $listenerType = trim($listenerType);
                if ($listenerType === $type) {
                    if (StringUtils::isNotEmpty($listenerPaths)) {
                        $listenerPathArr = explode(FlowCons::SPLIT_AT, $listenerPaths);
                        $listenerPath    = trim($listenerPathArr[$i] ?? '');

                        $valueHolder = new ValueHolder();
                        // 截取出 path 和 params
                        self::getListenerPath($listenerPath, $valueHolder);

                        $expressionMap = MapUtil::newAndPut('listenerVariable', $listenerVariable);
                        // 如果返回为 true，说明配置的 path 是表达式，并且已经执行完，不需要执行后续加载类路径（优先执行表达式监听器）
                        if (ExpressionUtil::evalListener($listenerPath, $expressionMap)) {
                            return;
                        }

//                        $clazz = ClassUtil::getClazz($valueHolder->getPath());
                        $clazz = $valueHolder->getPath();
                        // 增加传入类路径校验 Listener 接口，防止强制类型转换失败
                        if (ObjectUtil::isNotNull($clazz) && is_subclass_of($clazz, Listener::class)) {
                            /** @var Listener $listener */
                            $listener = FrameInvoker::getBean($clazz);
                            if (ObjectUtil::isNotNull($listener)) {
                                $variable = $listenerVariable->getVariable();
                                if (MapUtil::isEmpty($variable)) {
                                    $variable = [];
                                } else {
                                    unset($variable[FlowCons::WARM_LISTENER_PARAM]);
                                }
                                if (StringUtils::isNotEmpty($valueHolder->getParams())) {
                                    $variable[FlowCons::WARM_LISTENER_PARAM] = $valueHolder->getParams();
                                }
                                $listener->notify($listenerVariable->setVariable($variable));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 分别截取监听器 path 和监听器 params
     * 例如：listenerPath({"name": "John Doe", "age": 30})
     *
     * @param string $listenerStr 监听器字符串
     * @param ValueHolder $valueHolder 值持有者
     * @return void
     */
    public static function getListenerPath(string $listenerStr, ValueHolder $valueHolder): void
    {
        $path   = '';
        $params = '';

        $pattern = FlowCons::LISTENER_PATTERN;
        if (preg_match($pattern, $listenerStr, $matches)) {
            $path   = preg_replace('/[()]/', '', $matches[1] ?? '');
            $params = preg_replace('/[()]/', '', $matches[2] ?? '');
            $valueHolder->setPath($path);
            $valueHolder->setParams($params);
        }
    }
}
