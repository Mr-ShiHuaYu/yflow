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

use Yflow\core\enums\UserType;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\dao\IFlowUserDao;
use Yflow\core\orm\service\impl\WarmServiceImpl;
use Yflow\core\service\UserService;
use Yflow\core\utils\ArrayUtil;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\StreamUtils;


/**
 * UserServiceImpl - 流程用户 Service 业务层处理
 *
 * @author xiarg
 * @since 2024/5/10 13:57
 */
class UserServiceImpl extends WarmServiceImpl implements UserService
{

    public function __construct()
    {
        $this->setDao(FrameInvoker::getBean(IFlowUserDao::class));
    }

    /**
     * 设置 DAO
     *
     * @param IFlowUserDao $warmDao DAO
     * @return UserService
     */
    public function setDao($warmDao): UserService
    {
        $this->warmDao = $warmDao;
        return $this;
    }

    /**
     * 设置流程用户
     *
     * @param array $addTasks 待办任务
     * @return array List<User>
     */
    public function taskAddUsers(array $addTasks): array
    {
        $taskUserList = [];
        if (CollUtil::isNotEmpty($addTasks)) {
            foreach ($addTasks as $task) {
                $users = $this->taskAddUser($task);
                foreach ($users as $user) {
                    $taskUserList[] = $user;
                }
            }
        }
        return $taskUserList;
    }

    /**
     * 待办任务增加流程人员
     *
     * @param IFlowTaskDao $task 待办任务任务信息
     * @return array<IFlowUserDao>
     */
    public function taskAddUser(IFlowTaskDao $task): array
    {
        // 遍历权限集合，生成流程节点的权限
        $userList = StreamUtils::toList($task->getPermissionList()
            , fn($permission) => $this->structureUserItem($task->getId(), $permission, UserType::APPROVAL->value));
        $task->setUserList($userList);
        return $userList;
    }

    /**
     * 构造单个用户信息（内部方法）
     *
     * @param int $associated 关联 id
     * @param string $permission 权限标识
     * @param string $type 用户类型
     * @param string|null $handler 办理人（记录委派人）
     * @return IFlowUserDao|null
     */
    private function structureUserItem(int $associated, string $permission, string $type, ?string $handler = null): ?IFlowUserDao
    {
        return $this->structureSingleUserWithHandler($associated, $permission, $type, $handler);
    }

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param string $permission 权限标识
     * @param string $type 用户类型
     * @param string|null $handler 办理人（记录委派人）
     * @return IFlowUserDao|null
     */
    public function structureSingleUserWithHandler(int $associated, string $permission, string $type, ?string $handler = null): ?IFlowUserDao
    {
//        $now = date('Y-m-d H:i:s');
        $user = FlowEngine::newUser()
            ->setType($type)
            ->setProcessedBy($permission)
            ->setAssociated($associated)
            ->setCreateBy($handler);
        FlowEngine::dataFillHandler()->idFill($user);
        return $user;
    }

    /**
     * 根据待办任务 id 删除流程用户
     *
     * @param array $ids 待办任务 id 集合
     * @return void
     */
    public function deleteByTaskIds(array $ids): void
    {
        /**
         * @var IFlowUserDao $dao
         */
        $dao = $this->getDao();
        $dao->deleteByTaskIds($ids);
    }

    /**
     * 根据 (待办任务，实例，历史表，节点等)id 查询权限人或者处理人
     *
     * @param int $associated 待办任务 id
     * @param string ...$types 用户表类型
     * @return array<string>
     */
    public function getPermission(int $associated, string ...$types): array
    {
        if (ArrayUtil::isEmpty($types)) {
            return StreamUtils::toList($this->list(FlowEngine::newUser()->setAssociated($associated)), fn($user) => $user->getProcessedBy());
        }
        if (count($types) === 1) {
            return StreamUtils::toList($this->list(FlowEngine::newUser()->setAssociated($associated)->setType($types[0]))
                , fn($user) => $user->getProcessedBy());
        }
        /**
         * @var IFlowUserDao $dao
         */
        $dao = $this->getDao();
        return StreamUtils::toList($dao->listByAssociatedAndTypes([$associated], $types)
            , fn($user) => $user->getProcessedBy());
    }

    /**
     * 根据 (待办任务，实例，历史表，节点等)id 查询权限人或者处理人
     *
     * @param int $associated 待办任务 id
     * @param string ...$types 用户表类型
     * @return array<IFlowUserDao> 用户列表
     */
    public function listByAssociatedAndTypes(int $associated, string ...$types): array
    {
        if (ArrayUtil::isEmpty($types)) {
            return $this->list(FlowEngine::newUser()->setAssociated($associated));
        }
        if (count($types) === 1) {
            return $this->list(FlowEngine::newUser()->setAssociated($associated)->setType($types[0]));
        }
        /**
         * @var IFlowUserDao $dao
         */
        $dao = $this->getDao();
        return $dao->listByAssociatedAndTypes([$associated], $types);
    }

    /**
     * 根据 (待办任务，实例，历史表，节点等)id 查询权限人或者处理人
     *
     * @param array $associateds (待办任务，实例，历史表，节点等)id 集合
     * @param string ...$types 用户表类型
     * @return array<IFlowUserDao> 用户列表
     */
    public function getByAssociateds(array $associateds, string ...$types): array
    {
        if (CollUtil::isNotEmpty($associateds) && count($associateds) === 1) {
            return $this->listByAssociatedAndTypes($associateds[0], ...$types);
        }
        /**
         * @var IFlowUserDao $dao
         */
        $dao = $this->getDao();
        return $dao->listByAssociatedAndTypes($associateds, $types);
    }

    /**
     * 根据办理人查询
     *
     * @param int $associated 待办任务 id
     * @param array $processedBys 办理人 id 集合
     * @param string ...$types 用户表类型
     * @return array<IFlowUserDao>
     */
    public function getByProcessedBys(int $associated, array $processedBys, string ...$types): array
    {
        if (CollUtil::isNotEmpty($processedBys) && count($processedBys) === 1) {
            return $this->listByProcessedBys($associated, $processedBys[0], ...$types);
        }
        /**
         * @var IFlowUserDao $dao
         */
        $dao = $this->getDao();
        return $dao->listByProcessedBys($associated, $processedBys, $types);
    }

    /**
     * 根据办理人查询，返回集合
     *
     * @param int $associated 待办任务 id
     * @param string $processedBy 办理人
     * @param string ...$types 用户表类型
     * @return array<IFlowUserDao> 用户列表
     */
    public function listByProcessedBys(int $associated, string $processedBy, string ...$types): array
    {
        if (ArrayUtil::isEmpty($types)) {
            return $this->list(FlowEngine::newUser()->setAssociated($associated)->setProcessedBy($processedBy));
        }
        if (count($types) === 1) {
            return $this->list(FlowEngine::newUser()->setAssociated($associated)->setProcessedBy($processedBy)->setType($types[0]));
        }
        /**
         * @var IFlowUserDao $dao
         */
        $dao = $this->getDao();
        return $dao->listByProcessedBys($associated, [$processedBy], $types);
    }

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
    public function updatePermission(int $associated, array $permissions, string $type, bool $clear = false, ?string $handler = null): bool
    {
        // 判断是否 clear，如果是 true，则先删除当前关联 id 用户数据
        if ($clear) {
            $this->getDao()->where('associated', $associated)->where('create_by', $handler)->delete();
        }
        // 再新增权限人
        $this->saveBatch(StreamUtils::toList($permissions, fn($permission) => $this->structureUserItem($associated, $permission, $type, $handler)));
        return true;
    }

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param array $permissionList 权限标识集合
     * @param string $type 用户类型
     * @return array<IFlowUserDao>
     */
    public function structureUser(int $associated, array $permissionList, string $type): array
    {
        return StreamUtils::toList($permissionList, fn($permission) => $this->structureUserItem($associated, $permission, $type));
    }

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param string $permission 权限标识
     * @param string $type 用户类型
     * @return IFlowUserDao|null
     */
    public function structureSingleUser(int $associated, string $permission, string $type): ?IFlowUserDao
    {
        return $this->structureUserItem($associated, $permission, $type);
    }

    /**
     * 构造用户比表信息
     *
     * @param int $associated 关联 id
     * @param array $permissionList 权限标识集合
     * @param string $type 用户类型
     * @param string|null $handler 办理人（记录委派人）
     * @return array<IFlowUserDao>
     */
    public function structureUserWithHandler(int $associated, array $permissionList, string $type, ?string $handler = null): array
    {
        return StreamUtils::toList($permissionList, fn($permission) => $this->structureUserItem($associated, $permission, $type, $handler));
    }
}
