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

use Yflow\core\dto\FlowDto;
use Yflow\core\dto\FlowParams;
use Yflow\core\orm\dao\IFlowDefinitionDao;
use Yflow\core\orm\dao\IFlowInstanceDao;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\service\IWarmService;


/**
 * TaskService - 待办任务 Service 接口
 *
 *
 * @since 2023-03-29
 */
interface TaskService extends IWarmService
{

    /**
     * 流程通过
     */
    public function pass(int $taskId, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao;

    /**
     * 流程任意通过
     */
    public function passAtWill(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao;

    /**
     * 流程通过，并且自定义流程状态
     */
    public function passWithStatus(int $taskId, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao;

    /**
     * 流程任意通过，并且自定义流程状态
     */
    public function passAtWillWithStatus(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao;

    /**
     * 流程退回
     */
    public function reject(int $taskId, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao;

    /**
     * 流程任意退回
     */
    public function rejectAtWill(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao;

    /**
     * 流程退回，并且自定义流程状态
     */
    public function rejectWithStatus(int $taskId, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao;

    /**
     * 流程任意退回，并且自定义流程状态
     */
    public function rejectAtWillWithStatus(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao;

    /**
     * 根据任务 id，流程跳转
     */
    public function skip(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 根据实例 id，流程跳转
     */
    public function skipByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 驳回上一个任务（根据实例 ID）
     */
    public function rejectLastByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 驳回上一个任务（根据任务 ID）
     */
    public function rejectLast(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 驳回上一个任务（根据 IFlowTaskDao 对象）
     */
    public function rejectLastByTask(IFlowTaskDao $task, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 拿回到最近办理的任务（根据实例 ID）
     */
    public function taskBackByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 拿回到最近办理的任务（根据任务 ID）
     */
    public function taskBack(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 流程跳转（通用方法）
     */
    public function skipByParams(FlowParams $flowParams, IFlowTaskDao $task): ?IFlowInstanceDao;

    /**
     * 撤销
     */
    public function revoke(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 终止流程（根据实例 ID）
     */
    public function terminationByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 终止流程（根据任务 ID）
     */
    public function termination(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 终止流程（根据 IFlowTaskDao 对象）
     */
    public function terminationByTask(IFlowTaskDao $task, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 根据 instanceIds 删除
     */
    public function deleteByInsIds(array $instanceIds): bool;

    /**
     * 转办
     */
    public function transfer(int $taskId, FlowParams $flowParams): bool;

    /**
     * 委派
     */
    public function depute(int $taskId, FlowParams $flowParams): bool;

    /**
     * 加签
     */
    public function addSignature(int $taskId, FlowParams $flowParams): bool;

    /**
     * 减签
     */
    public function reductionSignature(int $taskId, FlowParams $flowParams): bool;

    /**
     * 修改办理人
     */
    public function updateHandler(int $taskId, FlowParams $flowParams): bool;

    /**
     * 设置流程待办任务对象
     */
    public function addTask(IFlowNodeDao $node, IFlowInstanceDao $instance, IFlowDefinitionDao $definition, FlowParams $flowParams): ?IFlowTaskDao;

    /**
     * 根据流程实例 id 获取流程任务集合
     *
     * @param int $instanceId 流程实例 id
     * @return array 任务集合
     */
    public function getByInsId(int $instanceId): array;

    /**
     * 根据流程实例 id 和节点 code 集合获取流程任务集合
     */
    public function getByInsIdAndNodeCodes(int $instanceId, array $nodeCodes): array;

    /**
     * 设置任务完成后的实例相关信息
     */
    public function setInsFinishInfo(IFlowInstanceDao $instance, array $addTasks, FlowParams $flowParams): void;

    /**
     * 合并流程变量到实例对象
     */
    public function mergeVariable(IFlowInstanceDao $instance, ?array $variable): void;

    /**
     * 获取表单及数据 (使用表单场景)
     */
    public function load(int $taskId, FlowParams $flowParams): ?FlowDto;

    /**
     * 获取表单及数据 (使用历史表场景)
     */
    public function hisLoad(int $hisTaskId, FlowParams $flowParams): ?FlowDto;
}
