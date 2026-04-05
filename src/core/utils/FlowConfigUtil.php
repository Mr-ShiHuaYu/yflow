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

use Yflow\core\constant\ExceptionCons;
use Yflow\core\dto\FlowCombine;
use Yflow\core\enums\NodeType;
use Yflow\core\enums\SkipType;
use Yflow\core\exception\FlowException;
use Yflow\core\FlowEngine;
use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowNodeModel;


/**
 * 流程配置帮助类
 *
 * @author zhoukai
 */
class FlowConfigUtil
{
    /**
     * 私有构造函数，防止实例化
     */
    private function __construct()
    {
    }

    /**
     * 构建流程数据
     *
     * @param FlowDefinitionModel $definition 流程定义
     * @return FlowCombine
     * @throws FlowException
     */
    public static function structureFlow(FlowDefinitionModel $definition): FlowCombine
    {
        // 获取流程
        $combine = new FlowCombine();
        // 流程定义
        $combine->setDefinition($definition);
        // 所有的流程节点
        $allNodes = $combine->getAllNodes();
        // 所有的流程连线
        $allSkips = $combine->getAllSkips();

        $flowName = $definition->getFlowName();
        AssertUtil::isEmpty($definition->getFlowCode(), "【" . $flowName . "】流程 flowCode 为空!");
        // 发布
        $definition->setIsPublish(0);
        $definition->setUpdateTime(date('Y-m-d H:i:s'));
        FlowEngine::dataFillHandler()->idFill($definition);

        $nodeList = $definition->getNodeList();
        // 每一个流程的开始节点个数
        $startNum    = 0;
        $nodeCodeSet = [];
        // 遍历一个流程中的各个节点
        foreach ($nodeList as $node) {
            self::initNodeAndCondition($node, $definition->getId(), $definition->getVersion());
            $startNum   = self::checkStartAndSame($node, $startNum, $flowName, $nodeCodeSet);
            $allNodes[] = $node;
            $allSkips   = array_merge($allSkips, $node->getSkipList());
        }
        $skipMap = StreamUtils::toMap($allNodes, fn($n) => $n->getNodeCode(), fn($n) => $n->getNodeType()); // 已经检查,是对的
        foreach ($allSkips as $allSkip) {
            $allSkip->setNextNodeType($skipMap[$allSkip->getNextNodeCode()] ?? null);
        }
        AssertUtil::isTrue($startNum === 0, "[" . $flowName . "]" . ExceptionCons::LOST_START_NODE);
        // 校验跳转节点的合法性
        self::checkSkipNode($allSkips);
        // 校验所有目标节点是否都存在
        self::validaIsExistDestNode($allSkips, $nodeCodeSet);
        $combine->setAllNodes($allNodes);
        $combine->setAllSkips($allSkips);
        return $combine;
    }

    /**
     * 读取工作节点和跳转条件
     *
     * @param FlowNodeModel $node 节点
     * @param int $definitionId 流程定义 ID
     * @param string $version 版本号
     * @throws FlowException
     */
    public static function initNodeAndCondition(FlowNodeModel $node, int $definitionId, string $version): void
    {
        $nodeName = $node->getNodeName();
        $nodeCode = $node->getNodeCode();
        $skipList = $node->getSkipList();
        if (!NodeType::isEnd($node->getNodeType())) {
            AssertUtil::isEmpty($skipList, ExceptionCons::MUST_SKIP);
        }
        AssertUtil::isEmpty($nodeCode, "[" . $nodeName . "]" . ExceptionCons::LOST_NODE_CODE);

        $node->setVersion($version);
        $node->setDefinitionId($definitionId);

        // 中间节点的集合，跳转类型和目标节点不能重复
        $betweenSet = [];
        // 网关的集合 跳转条件和下目标节点不能重复
        $gateWaySet = [];
        $skipNum    = 0;
        // 遍历节点下的跳转条件
        if (CollUtil::isEmpty($skipList)) {
            return;
        }
        foreach ($skipList as $skip) {
            if (NodeType::isStart($node->getNodeType())) {
                $skipNum++;
                AssertUtil::isTrue($skipNum > 1, "[" . $node->getNodeName() . "]" . ExceptionCons::MUL_START_SKIP);
            }
            AssertUtil::isEmpty($skip->getNextNodeCode(), "【" . $nodeName . "】" . ExceptionCons::LOST_DEST_NODE);
            // 流程 id
            $skip->setDefinitionId($definitionId);
            $skip->setNowNodeType($node->getNodeType());
            if (NodeType::isGateWaySerial($node->getNodeType())) {
                $target = $skip->getSkipCondition() . ":" . $skip->getNextNodeCode();
                AssertUtil::contains($gateWaySet, $target, "[" . $nodeName . "]" . ExceptionCons::SAME_CONDITION_NODE);
                $gateWaySet[] = $target;
            } else if (NodeType::isGateWayParallel($node->getNodeType())) {
                $target = $skip->getNextNodeCode();
                AssertUtil::contains($gateWaySet, $target, "[" . $nodeName . "]" . ExceptionCons::SAME_DEST_NODE);
                $gateWaySet[] = $target;
            } else {
                $value = $skip->getSkipType() . ":" . $skip->getNextNodeCode();
                AssertUtil::contains($betweenSet, $value, "[" . $nodeName . "]" . ExceptionCons::SAME_CONDITION_VALUE);
                $betweenSet[] = $value;
            }
        }
    }

    /**
     * 检查开始节点和重复节点
     *
     * @param FlowNodeModel $node 节点
     * @param int $startNum 开始节点数量
     * @param string $flowName 流程名称
     * @param array $nodeCodeSet 节点编码集合
     * @return int 开始节点数量
     * @throws FlowException
     */
    public static function checkStartAndSame(FlowNodeModel $node, int $startNum, string $flowName, array &$nodeCodeSet): int
    {
        if (NodeType::isStart($node->getNodeType())) {
            $startNum++;
            AssertUtil::isTrue($startNum > 1, "[" . $flowName . "]" . ExceptionCons::MUL_START_NODE);
        }
        // 保证不存在重复的 nodeCode
        AssertUtil::contains($nodeCodeSet, $node->getNodeCode(),
            "【" . $flowName . "】" . ExceptionCons::SAME_NODE_CODE);
        $nodeCodeSet[] = $node->getNodeCode();
        return $startNum;
    }

    /**
     * 校验跳转节点的合法性
     *
     * @param array $allSkips 所有跳转
     * @throws FlowException
     */
    public static function checkSkipNode(array $allSkips): void
    {
        $allSkipMap = StreamUtils::groupByKey($allSkips, fn($s) => $s->getNowNodeCode());
        // 不可同时通过或者退回到多个中间节点，必须先流转到网关节点
        foreach ($allSkipMap as $values) {
            $passNum   = 0;
            $rejectNum = 0;
            foreach ($values as $value) {
                if (NodeType::isBetween($value->getNowNodeType()) && NodeType::isBetween($value->getNextNodeType())) {
                    if (SkipType::isPass($value->getSkipType())) {
                        $passNum++;
                    } else {
                        $rejectNum++;
                    }
                }
            }
            AssertUtil::isTrue($passNum > 1 || $rejectNum > 1, ExceptionCons::MUL_SKIP_BETWEEN);
        }
    }

    /**
     * 校验所有的目标节点是否存在
     *
     * @param array $allSkips 所有跳转
     * @param array $nodeCodeSet 节点编码集合
     * @throws FlowException
     */
    public static function validaIsExistDestNode(array $allSkips, array $nodeCodeSet): void
    {
        foreach ($allSkips as $allSkip) {
            if (is_object($allSkip)) {
                $nextNodeCode = $allSkip->getNextNodeCode();
                AssertUtil::isTrue(!in_array($nextNodeCode, $nodeCodeSet), "【" . $nextNodeCode . "】" . ExceptionCons::NULL_NODE_CODE);
            }
        }
    }
}
