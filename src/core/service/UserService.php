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

use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\dao\IFlowUserDao;
use Yflow\core\orm\service\IWarmService;


/**
 * UserService - 流程用户 Service 接口
 *
 * @author xiarg
 * @since 2024/5/10 13:55
 */
interface UserService extends IWarmService
{

    /**
     * 设置流程用户
     *
     * @param array $addTasks 待办任务
     * @return array List<User>
     */
    public function taskAddUsers(array $addTasks): array;

    /**
     * 待办任务增加流程人员
     *
     * @param IFlowTaskDao $task 待办任务任务信息
     * @return array List<User>
     */
    public function taskAddUser(IFlowTaskDao $task): array;

    /**
     * 根据待办任务 id 删除流程用户
     *
     * @param array $ids 待办任务 id 集合
     * @return void
     */
    public function deleteByTaskIds(array $ids): void;

    /**
     * 根据 (待办任务，实例，历史表，节点等)id 查询权限人或者处理人
     *
     * @param int $associated 待办任务 id 集合
     * @param array $type 用户表类型
     * @return array<string>
     */
    public function getPermission(int $associated, string ...$type): array;

    /**
     * 根据 (待办任务，实例，历史表，节点等)id 查询权限人或者处理人
     *
     * @param int $associated 待办任务 id
     * @param array $types 用户表类型
     * @return array<IFlowUserDao> 用户列表
     */
    public function listByAssociatedAndTypes(int $associated, string ...$types): array;

    /**
     * 根据 (待办任务，实例，历史表，节点等)id 查询权限人或者处理人
     *
     * @param array $associateds (待办任务，实例，历史表，节点等)id 集合
     * @param array $types 用户表类型
     * @return array<IFlowUserDao>
     */
    public function getByAssociateds(array $associateds, string ...$types): array;

    /**
     * 根据办理人查询，返回集合
     *
     * @param int $associated 待办任务 id
     * @param string $processedBy 办理人
     * @param array $types 用户表类型
     * @return array<IFlowUserDao> 用户列表
     */
    public function listByProcessedBys(int $associated, string $processedBy, string ...$types): array;

    /**
     * 根据办理人查询
     *
     * @param int $associated 待办任务 id
     * @param array $processedBys 办理人 id 集合
     * @param array $types 用户表类型
     * @return array<IFlowUserDao>
     */
    public function getByProcessedBys(int $associated, array $processedBys, string ...$types): array;

    /**
     * 根据关联 id 更新权限人
     *
     * @param int $associated 关联人 id
     * @param array $permissions 权限人
     * @param string $type 权限人类型
     * @param bool $clear 是否清空待办任务的计划审批人
     * @param string|null $handler 存储委派时的办理人
     * @return bool 结果
     */
    public function updatePermission(int $associated, array $permissions, string $type, bool $clear = false, ?string $handler = null): bool;

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param array $permissionList 权限标识集合
     * @param string $type 用户类型
     * @return array<IFlowUserDao
     */
    public function structureUser(int $associated, array $permissionList, string $type): array;

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param string $permission 权限标识
     * @param string $type 用户类型
     * @return IFlowUserDao|null
     */
    public function structureSingleUser(int $associated, string $permission, string $type): ?IFlowUserDao;

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param array $permissionList 权限标识集合
     * @param string $type 用户类型
     * @param string|null $handler 办理人（记录委派人）
     * @return array<IFlowUserDao>
     */
    public function structureUserWithHandler(int $associated, array $permissionList, string $type, ?string $handler = null): array;

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param string $permission 权限标识
     * @param string $type 用户类型
     * @param string|null $handler 办理人（记录委派人）
     * @return IFlowUserDao|null
     */
    public function structureSingleUserWithHandler(int $associated, string $permission, string $type, ?string $handler = null): ?IFlowUserDao;
}
