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

namespace Yflow\core\service;

use Yflow\core\dto\FlowCombine;
use Yflow\core\dto\PathWayData;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\service\IWarmService;


/**
 * NodeService - 流程节点 Service 接口
 *
 *
 * @since 2023-03-29
 */
interface NodeService extends IWarmService
{

    /**
     * 根据流程编码获取已发布流程节点集合
     *
     * @param string $flowCode 流程编码
     * @return array<IFlowNodeDao>
     */
    public function getPublishByFlowCode(string $flowCode): array;

    /**
     * 根据流程编码获取开启的唯一流程的流程节点集合
     *
     * @param array $nodeCodes 流程节点 code 集合
     * @param int $definitionId 流程定义 id
     * @return array<IFlowNodeDao> 流程节点列表
     */
    public function getByNodeCodes(array $nodeCodes, int $definitionId): array;

    /**
     * 根据节点 id 获取所有的前置节点集合
     *
     * @param int $nodeId 节点 id
     * @return array 所有的前置节点集合
     */
    public function previousNodeList(int $nodeId): array;

    /**
     * 根据流程定义 id 和当前节点 code 获取所有的前置节点集合
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 当前节点 code
     * @return array 所有的前置节点集合
     */
    public function previousNodeListByDefId(int $definitionId, string $nowNodeCode): array;

    /**
     * 根据节点 id 获取所有的后置节点集合
     *
     * @param int $nodeId 节点 id
     * @return array 所有的后置节点集合
     */
    public function suffixNodeList(int $nodeId): array;

    /**
     * 根据流程定义 id 和当前节点 code 获取所有的后置节点集合
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 当前节点 code
     * @return array 所有的后置点集合
     */
    public function suffixNodeListByDefId(int $definitionId, string $nowNodeCode): array;

    /**
     * 流程数据集合和当前节点 code 获取所有的后置节点集合
     *
     * @param string $nowNodeCode 当前节点 code
     * @param FlowCombine $flowCombine 流程数据集合
     * @return array 所有的后置点集合
     */
    public function suffixNodeListByFlowCombine(string $nowNodeCode, FlowCombine $flowCombine): array;

    /**
     * 根据流程定义 id 获取流程节点集合
     *
     * @param int $definitionId 流程定义 id
     * @return array<IFlowNodeDao> 所有的节点集合
     */
    public function getByDefId(int $definitionId): array;

    /**
     * 根据流程定义 id 和节点编码获取流程节点
     *
     * @param int $definitionId 流程定义 id
     * @param string $nodeCode 节点编码
     * @return IFlowNodeDao|null 节点
     */
    public function getByDefIdAndNodeCode(int $definitionId, string $nodeCode): ?IFlowNodeDao;

    /**
     * 根据流程定义 id 获取开始节点
     *
     * @param int $definitionId 流程定义 id
     * @return IFlowNodeDao|null
     */
    public function getStartNode(int $definitionId): ?IFlowNodeDao;

    /**
     * 根据流程定义 id 获取中间节点集合
     *
     * @param int $definitionId 流程定义 id
     * @return array<IFlowNodeDao>
     */
    public function getBetweenNode(int $definitionId): array;

    /**
     * 根据流程定义 id 和流程变量获取第一个中间节点
     *
     * @param int $definitionId 流程定义 id
     * @param array|null $variable 流程变量
     * @return array<IFlowNodeDao>
     */
    public function getFirstBetweenNode(int $definitionId, ?array $variable): array;

    /**
     * 根据流程定义 id 获取结束节点
     *
     * @param int $definitionId 流程定义 id
     * @return IFlowNodeDao|null
     */
    public function getEndNode(int $definitionId): ?IFlowNodeDao;

    /**
     * 根据流程定义和当前节点 code 获取下一节点集合，如是网关跳过取下一节点，并行网关返回多个节点
     * 不一定是后置节点，如果是通过就是后置，如果是驳回就取前置节点
     *
     * @param int $definitionId 流程定义 id
     * @param string|null $nowNodeCode 当前节点 code
     * @param string|null $anyNodeCode anyNodeCode 不为空，则可跳转 anyNodeCode 节点（优先级最高）
     * @param string $skipType 跳转类型（PASS 审批通过 REJECT 退回）
     * @param array|null $variable 流程变量，下一个节点是网关需要判断跳转条件，并行网关返回多个节点
     * @return array<IFlowNodeDao>
     */
    public function getNextNodeList(int $definitionId, ?string $nowNodeCode, ?string $anyNodeCode, string $skipType, ?array $variable): array;

    /**
     * 根据流程定义 id 和当前节点获取下一节点
     * 不一定是后置节点，如果是通过就是后置，如果是驳回就取前置节点
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 当前节点 code
     * @param string|null $anyNodeCode anyNodeCode 不为空，则可跳转 anyNodeCode 节点（优先级最高）
     * @param string $skipType 跳转类型（PASS 审批通过 REJECT 退回）
     * @return IFlowNodeDao|null
     */
    public function getNextNodeByDefinitionId(int $definitionId, string $nowNodeCode, ?string $anyNodeCode, string $skipType): ?IFlowNodeDao;

    /**
     * 当前节点获取下一节点集合，如是网关跳过取下一节点，并行网关返回多个节点
     * 不一定是后置节点，如果是通过就是后置，如果是驳回就取前置节点
     *
     * @param IFlowNodeDao $nowNode 当前节点
     * @param string|null $anyNodeCode anyNodeCode 不为空，则可跳转 anyNodeCode 节点（优先级最高）
     * @param string $skipType 跳转类型（PASS 审批通过 REJECT 退回）
     * @param array|null $variable 流程变量，下一个节点是网关需要判断跳转条件，并行网关返回多个节点
     * @param PathWayData|null $pathWayData 办理过程中途径数据，用于渲染流程图
     * @param FlowCombine $flowCombine 流程数据集合
     * @return array<IFlowNodeDao>
     */
    public function getNextNodeListByNode(IFlowNodeDao $nowNode, ?string $anyNodeCode, string $skipType, ?array $variable, ?PathWayData $pathWayData, FlowCombine $flowCombine): array;

    /**
     * 根据当前节点获取下一节点
     * 不一定是后置节点，如果是通过就是后置，如果是驳回就取前置节点
     *
     * @param IFlowNodeDao $nowNode 当前节点
     * @param string|null $anyNodeCode anyNodeCode 不为空，则可跳转 anyNodeCode 节点（优先级最高）
     * @param string $skipType 跳转类型（PASS 审批通过 REJECT 退回）
     * @param PathWayData|null $pathWayData 办理过程中途径数据，用于渲染流程图
     * @param FlowCombine $flowCombine 流程数据集合
     * @return IFlowNodeDao|null
     */
    public function getNextNodeByNode(IFlowNodeDao $nowNode, ?string $anyNodeCode, string $skipType, ?PathWayData $pathWayData, FlowCombine $flowCombine): ?IFlowNodeDao;

    /**
     * 校验是否网关节点，如果是重新获取新的后面的节点
     *
     * @param array|null $variable 流程变量
     * @param IFlowNodeDao $nextNode 下一个节点
     * @param PathWayData $pathWayData 办理过程中途径数据，用于渲染流程图
     * @param FlowCombine $flowCombine 流程数据集合
     * @return array<IFlowNodeDao>
     */
    public function getNextByCheckGateway(?array $variable, IFlowNodeDao $nextNode, PathWayData $pathWayData, FlowCombine $flowCombine): array;

    /**
     * 批量删除流程节点
     *
     * @param array $defIds 需要删除的数据主键集合
     * @return int 结果
     */
    public function deleteNodeByDefIds(array $defIds): int;
}
