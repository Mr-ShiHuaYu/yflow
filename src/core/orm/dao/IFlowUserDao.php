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
 * IFlowUserDao - 流程用户 Mapper 接口
 *
 * @author xiarg
 * @since 2024/5/10 11:15
 */
interface IFlowUserDao extends IFlowBaseDao
{

    /**
     * 根据 taskId 删除
     *
     * @param array $taskIdList 待办任务主键集合
     * @return int 影响行数
     * @author xiarg
     * @since 2024/5/10 11:19
     */
    public function deleteByTaskIds(array $taskIdList): int;

    /**
     * 根据 (待办任务，实例，历史表，节点等) id 查询权限人或者处理人
     *
     * @param array $associatedList (待办任务，实例，历史表，节点等) id 集合
     * @param array $types 用户表类型数组
     * @return array<IFlowUserDao> 用户列表
     */
    public function listByAssociatedAndTypes(array $associatedList, array $types): array;

    /**
     * 根据办理人查询
     *
     * @param int $associated 待办任务 id
     * @param array $processedBys 办理人 id 集合
     * @param array $types 用户表类型数组
     * @return array<IFlowUserDao> 用户列表
     */
    public function listByProcessedBys(int $associated, array $processedBys, array $types): array;
}
