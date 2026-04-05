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

use Yflow\core\dto\FlowParams;
use Yflow\core\orm\dao\IFlowHisTaskDao;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\dao\IFlowUserDao;
use Yflow\core\orm\service\IWarmService;


/**
 * HisTaskService - 历史任务记录 Service 接口
 *
 *
 * @since 2023-03-29
 */
interface HisTaskService extends IWarmService
{

    /**
     * 根据任务 id 查询历史任务列表
     *
     * @param int $taskId 任务 id
     * @return array<IFlowHisTaskDao>
     */
    public function listByTaskId(int $taskId): array;

    /**
     * 根据任务 id 和协作类型查询
     *
     * @param int $taskId 任务 id
     * @param int ...$cooperateTypes 协作类型集合
     * @return array<IFlowHisTaskDao> 历史任务列表
     */
    public function listByTaskIdAndCooperateTypes(int $taskId, int ...$cooperateTypes): array;

    /**
     * 根据实例 Id 和节点编码查询
     *
     * @param int $instanceId 流程实例 id
     * @param array $nodeCodes 节点编码集合
     * @return array<IFlowHisTaskDao> 历史任务列表
     */
    public function getByInsAndNodeCodes(int $instanceId, array $nodeCodes): array;

    /**
     * 根据 instanceIds 删除
     *
     * @param array $instanceIds 流程实例 id 集合
     * @return bool
     */
    public function deleteByInsIds(array $instanceIds): bool;

    /**
     * 设置流程历史任务信息
     *
     * @param IFlowTaskDao $task 当前任务
     * @param array $nextNodes 后续任务
     * @param FlowParams $flowParams 参数
     * @return IFlowHisTaskDao|null
     */
    public function setSkipInsHis(IFlowTaskDao $task, array $nextNodes, FlowParams $flowParams): ?IFlowHisTaskDao;

    /**
     * 设置流程历史任务信息
     *
     * @param array $taskList 当前任务集合
     * @param array $nextNodes 后续任务
     * @param FlowParams $flowParams 参数
     * @return array<IFlowHisTaskDao>
     */
    public function setSkipHisList(array $taskList, array $nextNodes, FlowParams $flowParams): array;

    /**
     * 设置协作历史记录
     *
     * @param IFlowTaskDao $task 当前任务
     * @param IFlowNodeDao $node 当然任务节点
     * @param FlowParams $flowParams 参数
     * @param array $collaborators 协作人
     * @return IFlowHisTaskDao|null
     */
    public function setCooperateHis(IFlowTaskDao $task, IFlowNodeDao $node, FlowParams $flowParams, array $collaborators): ?IFlowHisTaskDao;

    /**
     * 委派历史任务
     *
     * @param IFlowTaskDao $task 当前任务
     * @param FlowParams $flowParams 参数
     * @param IFlowUserDao $entrustedUser 委托人
     * @return IFlowHisTaskDao|null 历史任务
     */
    public function setDeputeHisTask(IFlowTaskDao $task, FlowParams $flowParams, IFlowUserDao $entrustedUser): ?IFlowHisTaskDao;


    /**
     * 设置会签票签历史任务
     *
     * @param IFlowTaskDao $task 当前任务
     * @param FlowParams $flowParams 参数
     * @param string $nodeRatio 节点比率
     * @param bool $isPass 是否通过
     * @return IFlowHisTaskDao|null 历史任务
     */
    public function setSignHisTask(IFlowTaskDao $task, FlowParams $flowParams, string $nodeRatio, bool $isPass): ?IFlowHisTaskDao;

    /**
     * 设置流程历史任务信息
     *
     * @param IFlowTaskDao $task 当前任务
     * @param IFlowNodeDao $nextNode 跳转的节点
     * @param FlowParams $flowParams 流程参数
     * @return IFlowHisTaskDao|null 历史任务
     */
    public function setSkipHisTask(IFlowTaskDao $task, IFlowNodeDao $nextNode, FlowParams $flowParams): ?IFlowHisTaskDao;

    /**
     * 根据流程实例 id 查询历史任务
     *
     * @param int $instanceId 流程实例 id
     * @return array<IFlowHisTaskDao> 历史记录集合
     */
    public function getByInsId(int $instanceId): array;
}
