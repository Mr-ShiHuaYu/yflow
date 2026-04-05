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
use Yflow\core\orm\dao\IFlowInstanceDao;
use Yflow\core\orm\service\IWarmService;


/**
 * InsService - 流程实例 Service 接口
 *
 *
 * @since 2023-03-29
 */
interface InsService extends IWarmService
{

    /**
     * 传入业务 id 开启流程
     *
     * @param string $businessId 业务 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     *                    - flowCode: 流程编码 [必传]
     *                    - handler: 当前办理人唯一标识 [必传]
     *                    - variable: 流程变量 [按需传输]
     *                    - nextHandler: 执行的下个任务的办理人 [按需传输]
     *                    - nextHandlerAppend: 个任务处理人配置类型（true-追加，false-覆盖，默认 false）[按需传输]
     *                    - flowStatus: 流程状态，自定义流程状态 [按需传输]
     *                    - ext: 扩展字段，预留给业务系统使用 [按需传输]
     * @return IFlowInstanceDao|null 流程实例
     */
    public function start(string $businessId, FlowParams $flowParams): ?IFlowInstanceDao;

    /**
     * 根据实例 ids，删除流程及其相关任务
     *
     * @param mixed $instanceIds 流程实例集合
     * @return bool
     */
    public function removeWithTasks(array $instanceIds): bool;

    /**
     * 根据流程定义 id，查询流程实例集合
     *
     * @param int $definitionId 流程定义 id
     * @return array<IFlowInstanceDao>
     */
    public function getByDefId(int $definitionId): array;

    /**
     * 激活实例
     *
     * @param int $id 流程实例 id
     * @return bool
     */
    public function active(int $id): bool;

    /**
     * 挂起实例，流程实例挂起后，该流程实例无法继续流转
     *
     * @param int $id 流程实例 id
     * @return bool
     */
    public function unActive(int $id): bool;

    /**
     * 根据流程定义 id 集合，查询流程实例集合
     *
     * @param array $defIds 流程定义 id 集合
     * @return array<IFlowInstanceDao> 流程实例集合
     */
    public function listByDefIds(array $defIds): array;

    /**
     * 按照流程变量 key 删除流程变量
     *
     * @param int $instanceId 流程实例 id
     * @param array $keys 流程变量 key
     * @return void
     */
    public function removeVariables(int $instanceId, array $keys): void;
}
