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
 * IFlowNodeDao - 流程节点 Mapper 接口
 *
 *
 * @since 2023-03-29
 */
interface IFlowNodeDao extends IFlowBaseDao
{

    /**
     * 根据节点编码和定义 ID 查询节点列表
     *
     * @param array $nodeCodes 节点编码列表
     * @param int $definitionId 定义 ID
     * @return array<IFlowNodeDao> 节点列表
     */
    public function getByNodeCodes(array $nodeCodes, int $definitionId): array;


    /**
     * 批量删除流程节点
     *
     * @param array $defIds 需要删除的数据主键集合
     * @return int 影响行数
     */
    public function deleteNodeByDefIds(array $defIds): int;
}
