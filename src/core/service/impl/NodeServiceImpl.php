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

namespace Yflow\core\service\impl;

use Exception;
use Yflow\core\constant\ExceptionCons;
use Yflow\core\constant\FlowCons;
use Yflow\core\dto\FlowCombine;
use Yflow\core\dto\PathWayData;
use Yflow\core\enums\NodeType;
use Yflow\core\enums\PublishStatus;
use Yflow\core\enums\SkipType;
use Yflow\core\exception\FlowException;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\orm\dao\IFlowDefinitionDao;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\dao\IFlowSkipDao;
use Yflow\core\orm\service\impl\WarmServiceImpl;
use Yflow\core\service\NodeService;
use Yflow\core\utils\AssertUtil;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ExpressionUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\StreamUtils;
use Yflow\core\utils\StringUtils;


/**
 * NodeServiceImpl - 流程节点 Service 业务层处理
 *
 *
 * @since 2023-03-29
 */
class NodeServiceImpl extends WarmServiceImpl implements NodeService
{
    public function __construct()
    {
        $this->setDao(FrameInvoker::getBean(IFlowNodeDao::class));
    }

    /**
     * 设置 DAO
     *
     * @param IFlowNodeDao $warmDao DAO
     * @return NodeService
     */
    public function setDao($warmDao): NodeService
    {
        $this->warmDao = $warmDao;
        return $this;
    }

    /**
     * 根据流程编码获取已发布流程节点集合
     *
     * @param string $flowCode 流程编码
     * @return array<IFlowNodeDao>
     */
    public function getPublishByFlowCode(string $flowCode): array
    {
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = FlowEngine::defService()->getOne(FlowEngine::newDef()
            ->setFlowCode($flowCode)->setIsPublish(PublishStatus::PUBLISHED->value));
        if (ObjectUtil::isNotNull($definition)) {
            return $this->list(FlowEngine::newNode()->setDefinitionId($definition->getId()));
        }
        return [];
    }

    /**
     * 根据流程编码获取开启的唯一流程的流程节点集合
     *
     * @param array $nodeCodes 流程节点 code 集合
     * @param int $definitionId 流程定义 id
     * @return array<IFlowNodeDao> 流程节点列表
     */
    public function getByNodeCodes(array $nodeCodes, int $definitionId): array
    {
        /**
         * @var IFlowNodeDao $dao
         */
        $dao = $this->getDao();
        return $dao->getByNodeCodes($nodeCodes, $definitionId);
    }

    /**
     * 根据节点 id 获取所有的前置节点集合
     *
     * @param int $nodeId 节点 id
     * @return array 所有的前置节点集合
     */
    public function previousNodeList(int $nodeId): array
    {
        $nowNode = $this->getById($nodeId);
        return $this->previousNodeListByDefId($nowNode->getDefinitionId(), $nowNode->getNodeCode());
    }

    /**
     * 根据流程定义 id 和当前节点 code 获取所有的前置节点集合
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 当前节点 code
     * @return array 所有的前置节点集合
     */
    public function previousNodeListByDefId(int $definitionId, string $nowNodeCode): array
    {
        return $this->prefixOrSuffixNodesByDefId($definitionId, $nowNodeCode, FlowCons::PREVIOUS);
    }

    /**
     * 前缀或后缀节点
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 当前节点 code
     * @param string $type 类型
     * @return array<IFlowNodeDao>
     */
    private function prefixOrSuffixNodesByDefId(int $definitionId, string $nowNodeCode, string $type): array
    {
        $flowCombine = new FlowCombine();
        $flowCombine->setAllNodes($this->getByDefId($definitionId));
        $flowCombine->setAllSkips(FlowEngine::skipService()->getByDefId($definitionId));
        return $this->prefixOrSuffixNodes($nowNodeCode, $type, $flowCombine);
    }

    /**
     * 根据流程定义 id 获取流程节点集合
     *
     * @param int $definitionId 流程定义 id
     * @return array<IFlowNodeDao> 所有的节点集合
     */
    public function getByDefId(int $definitionId): array
    {
        return $this->list(FlowEngine::newNode()->setDefinitionId($definitionId));
    }

    /**
     * 前缀或后缀节点
     *
     * @param string $nowNodeCode 当前节点 code
     * @param string $type 类型
     * @param FlowCombine $flowCombine 流程数据集合
     * @return array<IFlowNodeDao>
     */
    private function prefixOrSuffixNodes(string $nowNodeCode, string $type, FlowCombine $flowCombine): array
    {
        $nodeMap = StreamUtils::toMap($flowCombine->getAllNodes(), fn($node) => $node->getNodeCode(), fn($node) => $node);
        $skipMap = [];
        foreach ($flowCombine->getAllSkips() as $skip) {
            if (SkipType::isPass($skip->getSkipType())) {
                $key = FlowCons::PREVIOUS === $type ? $skip->getNextNodeCode() : $skip->getNowNodeCode();
                if (!isset($skipMap[$key])) {
                    $skipMap[$key] = [];
                }
                $skipMap[$key][] = $skip;
            }
        }

        $prefixOrSuffixNodes = [];
        $prefixOrSuffixCode  = $this->prefixOrSuffixCodes($skipMap, $nowNodeCode
            , FlowCons::PREVIOUS === $type ? fn($skip) => $skip->getNowNodeCode() : fn($skip) => $skip->getNextNodeCode());
        foreach ($prefixOrSuffixCode as $nodeCode) {
            $node = $nodeMap[$nodeCode];
            if (!NodeType::isGateWay($node->getNodeType())) {
                $prefixOrSuffixNodes[] = $node;
            }
        }
        $prefixOrSuffixNodes = array_reverse($prefixOrSuffixNodes);
        $sameCode            = [];
        $prefixOrSuffixNodes = array_filter($prefixOrSuffixNodes, function ($node) use (&$sameCode) {
            if (in_array($node->getNodeCode(), $sameCode)) {
                return false;
            }
            $sameCode[] = $node->getNodeCode();
            return true;
        });
        return array_reverse($prefixOrSuffixNodes);
    }

    /**
     * 前缀或后缀编码
     *
     * @param array $skipMap 跳转映射
     * @param string $nodeCode 节点编码
     * @param callable $supplier 提供者
     * @return array List<String>
     */
    private function prefixOrSuffixCodes(array $skipMap, string $nodeCode, callable $supplier): array
    {
        // 记录已访问节点，防止循环
        $visited = [];
        $result  = [];
        $this->prefixOrSuffixCodesRecursive($skipMap, $nodeCode, $supplier, $visited, $result);
        return $result;
    }

    /**
     * 前缀或后缀编码递归
     *
     * @param array $skipMap 跳转映射
     * @param string $nodeCode 节点编码
     * @param callable $supplier 提供者
     * @param array $visited 已访问节点
     * @param array $result 结果
     * @return void
     */
    private function prefixOrSuffixCodesRecursive(array $skipMap, string $nodeCode, callable $supplier, array &$visited, array &$result): void
    {
        if (isset($visited[$nodeCode])) {
            // 防止循环访问
            return;
        }

        $visited[$nodeCode] = true;
        $skipList           = $skipMap[$nodeCode] ?? [];

        if (CollUtil::isNotEmpty($skipList)) {
            foreach ($skipList as $skip) {
                if (SkipType::isPass($skip->getSkipType())) {
                    $nextNodeCode = $supplier($skip);
                    // 避免重复添加
                    if (!in_array($nextNodeCode, $result)) {
                        $result[] = $nextNodeCode;
                    }
                    $this->prefixOrSuffixCodesRecursive($skipMap, $nextNodeCode, $supplier, $visited, $result);
                }
            }
        }
    }

    /**
     * 根据节点 id 获取所有的后置节点集合
     *
     * @param int $nodeId 节点 id
     * @return array 所有的后置节点集合
     */
    public function suffixNodeList(int $nodeId): array
    {
        $nowNode = $this->getById($nodeId);
        return $this->suffixNodeListByDefId($nowNode->getDefinitionId(), $nowNode->getNodeCode());
    }

    /**
     * 根据流程定义 id 和当前节点 code 获取所有的后置节点集合
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 当前节点 code
     * @return array 所有的后置点集合
     */
    public function suffixNodeListByDefId(int $definitionId, string $nowNodeCode): array
    {
        return $this->prefixOrSuffixNodesByDefId($definitionId, $nowNodeCode, FlowCons::SUFFIX);
    }

    /**
     * 流程数据集合和当前节点 code 获取所有的后置节点集合
     *
     * @param string $nowNodeCode 当前节点 code
     * @param FlowCombine $flowCombine 流程数据集合
     * @return array 所有的后置点集合
     */
    public function suffixNodeListByFlowCombine(string $nowNodeCode, FlowCombine $flowCombine): array
    {
        return $this->prefixOrSuffixNodes($nowNodeCode, FlowCons::SUFFIX, $flowCombine);
    }

    /**
     * 根据流程定义 id 和节点编码获取流程节点
     *
     * @param int $definitionId 流程定义 id
     * @param string $nodeCode 节点编码
     * @return IFlowNodeDao|null
     */
    public function getByDefIdAndNodeCode(int $definitionId, string $nodeCode): ?IFlowNodeDao
    {
        $entity = FlowEngine::newNode()->setDefinitionId($definitionId)->setNodeCode($nodeCode);
        /**
         * @var IFlowNodeDao|null $one
         */
        $one = $this->getOne($entity);
        return $one;
    }

    /**
     * 根据流程定义 id 获取开始节点
     *
     * @param int $definitionId 流程定义 id
     * @return IFlowNodeDao|null
     */
    public function getStartNode(int $definitionId): ?IFlowNodeDao
    {
        $entity = FlowEngine::newNode()->setDefinitionId($definitionId)->setNodeType(NodeType::START->value);
        /**
         * @var IFlowNodeDao|null $one
         */
        $one = $this->getOne($entity);
        return $one;
    }


    /**
     * 根据流程定义 id 获取中间节点集合
     *
     * @param int $definitionId 流程定义 id
     * @return array<IFlowNodeDao>
     */
    public function getBetweenNode(int $definitionId): array
    {
        return $this->list(FlowEngine::newNode()->setDefinitionId($definitionId)->setNodeType(NodeType::BETWEEN->value));
    }

    /**
     * 根据流程定义 id 和流程变量获取第一个中间节点
     *
     * @param int $definitionId 流程定义 id
     * @param array|null $variable 流程变量
     * @return array<IFlowNodeDao>
     * @throws FlowException
     */
    public function getFirstBetweenNode(int $definitionId, ?array $variable): array
    {
        $flowCombine = FlowEngine::defService()->getFlowCombineNoDef($definitionId);
        $startNode   = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($t) => NodeType::isStart($t->getNodeType()));
        return $this->getNextNodeListByNode($startNode, null, SkipType::PASS->value,
            $variable, null, $flowCombine);
    }

    /**
     * 根据当前节点获取下一节点集合
     *
     * @param IFlowNodeDao|null $nowNode 当前节点
     * @param string|null $anyNodeCode 任意跳转节点 code
     * @param string|null $skipType 跳转类型
     * @param array|null $variable 流程变量
     * @param PathWayData|null $pathWayData 办理过程中途径数据
     * @param FlowCombine|null $flowCombine 流程数据集合
     * @return array<IFlowNodeDao>
     * @throws FlowException
     */
    public function getNextNodeListByNode(?IFlowNodeDao $nowNode, ?string $anyNodeCode, ?string $skipType, ?array $variable, ?PathWayData $pathWayData, ?FlowCombine $flowCombine): array
    {
        // 如果是网关节点，则根据条件判断
        return $this->getNextByCheckGateway($variable, $this->getNextNodeByNode($nowNode, $anyNodeCode, $skipType
            , $pathWayData, $flowCombine), $pathWayData, $flowCombine);
    }

    /**
     * 校验是否网关节点，如果是重新获取新的后面的节点
     *
     * @param array|null $variable 流程变量
     * @param IFlowNodeDao|null $nextNode 下一个节点
     * @param PathWayData|null $pathWayData 办理过程中途径数据
     * @param FlowCombine $flowCombine 流程数据集合
     * @return array<IFlowNodeDao>
     * @throws FlowException
     * @throws Exception
     */
    public function getNextByCheckGateway(?array $variable, ?IFlowNodeDao $nextNode, ?PathWayData $pathWayData
        , FlowCombine                            $flowCombine): array
    {
        // 网关节点处理
        if (NodeType::isGateWay($nextNode->getNodeType())) {
            $skipsGateway = StreamUtils::filter($flowCombine->getAllSkips()
                , fn($skip) => $nextNode->getNodeCode() === $skip->getNowNodeCode());
            if (CollUtil::isEmpty($skipsGateway)) {
                return [];
            }

            //如果是互斥网关，跳转条件匹配的，则取任意第一条，否则取跳转条件为空的任意一条
            if (NodeType::isGateWaySerial($nextNode->getNodeType())) {
                $skipOne = null;
                foreach ($skipsGateway as $skip) {
                    if (StringUtils::isNotEmpty($skip->getSkipCondition())) {
                        if (ExpressionUtil::evalCondition($skip->getSkipCondition(), $variable)) {
                            $skipOne = $skip;
                            break;
                        }
                    } else {
                        $skipOne = $skip;
                    }
                }
                $skipsGateway = $skipOne === null ? [] : CollUtil::toList($skipOne);
            } elseif (NodeType::isGateWayInclusive($nextNode->getNodeType())) {
                //如果是包含网关，有跳转条件的分支，但是跳转条件不匹配的不执行，没跳转条件为空的分支默认执行
                $skipsGateway = array_filter($skipsGateway, fn($skip) => !StringUtils::isNotEmpty($skip->getSkipCondition())
                    || ExpressionUtil::evalCondition($skip->getSkipCondition(), $variable));
            }

            AssertUtil::isEmpty($skipsGateway, ExceptionCons::NULL_CONDITION_VALUE_NODE);
            $nextNodeCodes = StreamUtils::toList($skipsGateway, fn($skip) => $skip->getNextNodeCode());
            $nextNodes     = StreamUtils::filter($flowCombine->getAllNodes()
                , fn($node) => in_array($node->getNodeCode(), $nextNodeCodes));
            AssertUtil::isEmpty($nextNodes, ExceptionCons::NOT_NODE_DATA);
            if ($pathWayData !== null) {
                $pathWayNodes = $pathWayData->getPathWayNodes();
                $pathWayData->setPathWayNodes(array_merge($pathWayNodes, $nextNodes));
                $pathWaySkips = $pathWayData->getPathWaySkips();
                $pathWayData->setPathWaySkips(array_merge($pathWaySkips, $skipsGateway));
            }
            $newNextNodes = [];
            foreach ($nextNodes as $node) {
                $nodeList     = $this->getNextByCheckGateway($variable, $node, $pathWayData, $flowCombine);
                $newNextNodes = array_merge($newNextNodes, $nodeList);
            }
            return $newNextNodes;
        }
        // 非网关节点直接返回
        if ($pathWayData !== null) {
            $pathWayNodes = $pathWayData->getPathWayNodes();
            $index        = array_search($nextNode, $pathWayNodes);
            if ($index !== false) {
                unset($pathWayNodes[$index]);
                $pathWayData->setPathWayNodes($pathWayNodes);
            }
        }
        AssertUtil::isTrue(NodeType::isStart($nextNode->getNodeType()), ExceptionCons::START_NODE_NOT_ALLOW_JUMP);
        return CollUtil::toList($nextNode);
    }

    /**
     * 根据当前节点获取下一节点
     *
     * @param IFlowNodeDao|null $nowNode 当前节点
     * @param string|null $anyNodeCode 任意跳转节点 code
     * @param string|null $skipType 跳转类型
     * @param PathWayData|null $pathWayData 办理过程中途径数据
     * @param FlowCombine|null $flowCombine 流程数据集合
     * @return IFlowNodeDao|null
     * @throws FlowException
     */
    public function getNextNodeByNode(?IFlowNodeDao $nowNode, ?string $anyNodeCode, ?string $skipType, ?PathWayData $pathWayData, ?FlowCombine $flowCombine): ?IFlowNodeDao
    {
        // 查询当前节点
        AssertUtil::isNull($nowNode, ExceptionCons::LOST_NODE_CODE);
        AssertUtil::isNull($nowNode->getDefinitionId(), ExceptionCons::NOT_DEFINITION_ID);
        AssertUtil::isEmpty($skipType, ExceptionCons::NULL_CONDITION_VALUE);

        if ($pathWayData !== null) {
            $pathWayNodes   = $pathWayData->getPathWayNodes();
            $pathWayNodes[] = $nowNode;
            $pathWayData->setPathWayNodes($pathWayNodes);
        }
        $nextNode = null;
        if (StringUtils::isNotEmpty($anyNodeCode)) {
            // 如果指定了跳转节点，直接获取节点
            $nextNode = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($node) => $anyNodeCode === $node->getNodeCode());
        } elseif (StringUtils::isNotEmpty($nowNode->getAnyNodeSkip()) && SkipType::isReject($skipType)) {
            // 如果配置了任意跳转节点，直接获取节点
            $nextNode = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($node) => $nowNode->getAnyNodeSkip() === $node->getNodeCode());
        }

        if (ObjectUtil::isNotNull($nextNode)) {
            AssertUtil::isTrue(NodeType::isGateWay($nextNode->getNodeType()), ExceptionCons::TAR_NOT_GATEWAY);
            return $nextNode;
        }

        // 获取跳转关系
        $skips = StreamUtils::filter($flowCombine->getAllSkips(), fn($skip) => $nowNode->getNodeCode() === $skip->getNowNodeCode());
        AssertUtil::isNull($skips, ExceptionCons::NULL_DEST_NODE);
        $nextSkip = $this->getSkipByCheck($skips, $skipType);

        // 根据跳转查询出跳转到的那个节点
        $nextNode = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($node) => $nextSkip !== null && $nextSkip->getNextNodeCode() === $node->getNodeCode());
        AssertUtil::isNull($nextNode, ExceptionCons::NULL_NODE_CODE);
        AssertUtil::isTrue(NodeType::isStart($nextNode->getNodeType()), ExceptionCons::FIRST_FORBID_BACK);
        if ($pathWayData !== null) {
            $pathWayNodes   = $pathWayData->getPathWayNodes();
            $pathWayNodes[] = $nextNode;
            $pathWayData->setPathWayNodes($pathWayNodes);

            $pathWaySkips   = $pathWayData->getPathWaySkips();
            $pathWaySkips[] = $nextSkip;
            $pathWayData->setPathWaySkips($pathWaySkips);
        }
        return $nextNode;
    }

    /**
     * 通过校验跳转类型获取跳转集合
     *
     * @param array|null $skips 跳转集合
     * @param string $skipType 跳转类型
     * @return IFlowSkipDao
     * @throws FlowException
     */
    private function getSkipByCheck(?array $skips, string $skipType): IFlowSkipDao
    {
        if (CollUtil::isEmpty($skips)) {
            throw new FlowException(ExceptionCons::NULL_SKIP_TYPE);
        }
        foreach ($skips as $skip) {
            if (StringUtils::isEmpty($skip->getSkipType()) || $skipType === $skip->getSkipType()) {
                return $skip;
            }
        }
        throw new FlowException(ExceptionCons::NULL_SKIP_TYPE);
    }

    /**
     * 根据流程定义 id 获取结束节点
     *
     * @param int $definitionId 流程定义 id
     * @return IFlowNodeDao|null
     */
    public function getEndNode(int $definitionId): ?IFlowNodeDao
    {
        $entity = FlowEngine::newNode()->setDefinitionId($definitionId)->setNodeType(NodeType::END->value);
        /**
         * @var IFlowNodeDao|null $one
         */
        $one = $this->getOne($entity);
        return $one;
    }

    /**
     * 根据流程定义 id 和节点编码获取下一节点集合
     *
     * @param int $definitionId 流程定义 id
     * @param string|null $nowNodeCode 当前节点 code
     * @param string|null $anyNodeCode 任意跳转节点 code
     * @param string|null $skipType 跳转类型
     * @param array|null $variable 流程变量
     * @return array<IFlowNodeDao>
     * @throws FlowException
     */
    public function getNextNodeList(int    $definitionId, ?string $nowNodeCode, ?string $anyNodeCode, ?string $skipType,
                                    ?array $variable): array
    {
        AssertUtil::isEmpty($nowNodeCode, ExceptionCons::LOST_NODE_CODE);
        // 查询当前节点
        $flowCombine = FlowEngine::defService()->getFlowCombineNoDef($definitionId);
        $nowNode     = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($t) => $t->getNodeCode() === $nowNodeCode);
        // 如果是网关节点，则根据条件判断
        return $this->getNextByCheckGateway($variable, $this->getNextNodeByNode($nowNode, $anyNodeCode, $skipType, null, $flowCombine),
            null, $flowCombine);
    }

    /**
     * 根据流程定义 id 和当前节点获取下一节点
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 当前节点 code
     * @param string|null $anyNodeCode 任意跳转节点 code
     * @param string $skipType 跳转类型
     * @return IFlowNodeDao|null
     * @throws FlowException
     */
    public function getNextNodeByDefinitionId(int $definitionId, string $nowNodeCode, ?string $anyNodeCode, string $skipType): ?IFlowNodeDao
    {
        // 查询当前节点
        $flowCombine = FlowEngine::defService()->getFlowCombineNoDef($definitionId);
        $nowNode     = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($t) => $t->getNodeCode() === $nowNodeCode);
        return $this->getNextNodeByNode($nowNode, $anyNodeCode, $skipType, null, $flowCombine);
    }

    /**
     * 批量删除流程节点
     *
     * @param array $defIds 需要删除的数据主键集合
     * @return int 结果
     */
    public function deleteNodeByDefIds(array $defIds): int
    {
        return $this->getDao()->deleteNodeByDefIds($defIds);
    }
}
