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

namespace Yflow\core\orm\dao;

/**
 * IFlowHisTaskDao - 历史任务记录 Mapper 接口
 *
 *
 * @since 2023-03-29
 */
interface IFlowHisTaskDao extends IFlowBaseDao
{

    /**
     * 根据 instanceId 获取未退回的历史记录
     *
     * @param int $instanceId 实例 ID
     * @return array<IFlowHisTaskDao> 历史记录列表
     */
    public function getNoReject(int $instanceId): array;

    /**
     * 根据 instanceId 和节点编码获取未退回的历史记录
     *
     * @param int $instanceId 实例 ID
     * @param array $nodeCodes 节点编码列表
     * @return array<IFlowHisTaskDao> 历史记录列表
     */
    public function getByInsAndNodeCodes(int $instanceId, array $nodeCodes): array;

    /**
     * 根据 instanceIds 删除
     *
     * @param array $instanceIds 实例 ID 集合
     * @return int 影响行数
     */
    public function deleteByInsIds(array $instanceIds): int;

    /**
     * 根据任务 id 和协作类型查询
     *
     * @param int $taskId 任务 ID
     * @param array $cooperateTypes 协作类型数组
     * @return array<IFlowHisTaskDao> 历史记录列表
     */
    public function listByTaskIdAndCooperateTypes(int $taskId, array $cooperateTypes): array;
}
