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
use Yflow\core\dto\FlowParams;
use Yflow\core\dto\PathWayData;
use Yflow\core\enums\ActivityStatus;
use Yflow\core\enums\FlowStatus;
use Yflow\core\enums\NodeType;
use Yflow\core\enums\SkipType;
use Yflow\core\exception\FlowException;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;
use Yflow\core\orm\dao\IFlowHisTaskDao;
use Yflow\core\orm\dao\IFlowInstanceDao;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\service\impl\WarmServiceImpl;
use Yflow\core\service\InsService;
use Yflow\core\utils\AssertUtil;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ExpressionUtil;
use Yflow\core\utils\ListenerUtil;
use Yflow\core\utils\StreamUtils;
use Yflow\core\utils\StringUtils;


/**
 * InsServiceImpl - 流程实例 Service 业务层处理
 *
 *
 * @since 2023-03-29
 */
class InsServiceImpl extends WarmServiceImpl implements InsService
{
    public function __construct()
    {
        $this->setDao(FrameInvoker::getBean(IFlowInstanceDao::class));
    }


    /**
     * 设置 DAO
     *
     * @param IFlowInstanceDao $warmDao DAO
     * @return InsService
     */
    public function setDao($warmDao): InsService
    {
        $this->warmDao = $warmDao;
        return $this;
    }

    /**
     * 传入业务 id 开启流程
     *
     * @param string $businessId 业务 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null 流程实例
     * @throws FlowException
     * @throws Exception
     */
    public function start(string $businessId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        AssertUtil::isNull($flowParams->getFlowCode(), ExceptionCons::NULL_FLOW_CODE);
        AssertUtil::isEmpty($businessId, ExceptionCons::NULL_BUSINESS_ID);

        // 获取已发布的流程节点
        $definition = FlowEngine::defService()->getPublishByFlowCode($flowParams->getFlowCode());
        AssertUtil::isNull($definition, ExceptionCons::NOT_FOUNT_DEF);
        $flowCombine = FlowEngine::defService()->getFlowCombineByDef($definition);

        // 获取开始节点
        $startNode = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($t) => NodeType::isStart($t->getNodeType()));
        AssertUtil::isNull($startNode, ExceptionCons::LOST_START_NODE);

        // 判断流程定义是否激活状态
        AssertUtil::isTrue($definition->getActivityStatus() === ActivityStatus::SUSPENDED->value
            , ExceptionCons::NOT_DEFINITION_ACTIVITY);
        $flowParams->skipType(SkipType::PASS->value);

        // 执行开始监听器
        ListenerUtil::executeListener((new ListenerVariable($definition, null, $startNode, $flowParams->getVariable()))
            ->setFlowParams($flowParams), Listener::LISTENER_START);

        // 获取下一个节点，如果是网关节点，则重新获取后续节点
        $pathWayData = new PathWayData();
        $pathWayData->setDefId($startNode->getDefinitionId())->setSkipType($flowParams->getSkipType());
        $nextNodes = FlowEngine::nodeService()->getNextNodeListByNode($startNode, null, $flowParams->getSkipType(),
            $flowParams->getVariable(), $pathWayData, $flowCombine);

        // 设置流程实例对象
        $instance = $this->setStartInstance($nextNodes[0], $businessId, $flowParams);

        // 设置历史任务
        $hisTask = $this->setHisTask($nextNodes, $flowParams, $startNode, $instance->getId());

        $addTasks = [];
        foreach ($nextNodes as $node) {
            $addTasks[] = FlowEngine::taskService()->addTask($node, $instance, $definition, $flowParams);
        }

        // 办理人变量替换
        if (CollUtil::isNotEmpty($addTasks)) {
            ExpressionUtil::evalVariable($addTasks, $flowParams);
        }

        // 设置流程图元数据
        $targetNodes = $pathWayData->getTargetNodes();
        $pathWayData->setTargetNodes(array_merge($nextNodes, $targetNodes));
        $instance->setDefJson(FlowEngine::chartService()->startMetadata($pathWayData));

        // 执行分派监听器
        ListenerUtil::executeListener((new ListenerVariable($definition, $instance, $startNode, $flowParams->getVariable()
            , null, $nextNodes, $addTasks))->setFlowParams($flowParams), Listener::LISTENER_ASSIGNMENT);

        // 开启流程，保存流程信息
        $this->saveFlowInfo($instance, $addTasks, $hisTask, $flowParams);

        // 执行完成和创建监听器
        ListenerUtil::endCreateListener((new ListenerVariable($definition, $instance, $startNode, $flowParams->getVariable()
            , null, $nextNodes, $addTasks))->setFlowParams($flowParams));

        return $instance;
    }

    /**
     * 根据流程定义 id 集合，查询流程实例集合
     *
     * @param array $defIds 流程定义 id 集合
     * @return array<IFlowInstanceDao>
     */
    public function listByDefIds(array $defIds): array
    {
        /**
         * @var IFlowInstanceDao $dao
         */
        $dao = $this->getDao();
        return $dao->getByDefIds($defIds);
    }

    /**
     * 根据实例 ids，删除流程及其相关任务
     *
     * @param mixed $instanceIds 流程实例集合
     * @return bool
     * @throws FlowException
     */
    public function removeWithTasks(array $instanceIds): bool
    {
        return $this->toRemoveTask($instanceIds);
    }

    /**
     * 根据流程定义 id，查询流程实例集合
     *
     * @param int $definitionId 流程定义 id
     * @return array<IFlowInstanceDao>
     */
    public function getByDefId(int $definitionId): array
    {
        return $this->list(FlowEngine::newIns()->setDefinitionId($definitionId));
    }

    /**
     * 激活实例
     *
     * @param int $id 流程实例 id
     * @return bool
     * @throws FlowException
     */
    public function active(int $id): bool
    {
        /**
         * @var IFlowInstanceDao $instance
         */
        $instance = $this->getById($id);
        AssertUtil::isTrue(ActivityStatus::isActivity($instance->getActivityStatus()), ExceptionCons::INSTANCE_ALREADY_ACTIVITY);
        $instance->setActivityStatus(ActivityStatus::ACTIVITY->value);
        return $this->updateById($instance);
    }

    /**
     * 挂起实例，流程实例挂起后，该流程实例无法继续流转
     *
     * @param int $id 流程实例 id
     * @return bool
     * @throws FlowException
     */
    public function unActive(int $id): bool
    {
        /**
         * @var IFlowInstanceDao $instance
         */
        $instance = $this->getById($id);
        AssertUtil::isTrue(ActivityStatus::isSuspended($instance->getActivityStatus()), ExceptionCons::INSTANCE_ALREADY_ACTIVITY);
        $instance->setActivityStatus(ActivityStatus::SUSPENDED->value);
        return $this->updateById($instance);
    }

    /**
     * 按照流程变量 key 删除流程变量
     *
     * @param int $instanceId 流程实例 id
     * @param array $keys 流程变量 key
     * @return void
     */
    public function removeVariables(int $instanceId, array $keys): void
    {
        /**
         * @var IFlowInstanceDao $instance
         */
        $instance = $this->getById($instanceId);
        if ($instance !== null) {
            $variableMap = $instance->getVariableMap();
            foreach ($keys as $key) {
                unset($variableMap[$key]);
            }
            $instance->setVariable(FlowEngine::$jsonConvert->objToStr($variableMap));
            FlowEngine::insService()->updateById($instance);
        }
    }

    /**
     * 设置历史任务
     *
     * @param array $nextNodes 下一节点集合
     * @param FlowParams $flowParams 流程参数
     * @param IFlowNodeDao $startNode 开始节点
     * @param int $instanceId 流程实例 id
     * @return IFlowHisTaskDao|null
     */
    private function setHisTask(array $nextNodes, FlowParams $flowParams, IFlowNodeDao $startNode, int $instanceId): ?IFlowHisTaskDao
    {
        $startTask = FlowEngine::newTask()
            ->setInstanceId($instanceId)
            ->setDefinitionId($startNode->getDefinitionId())
            ->setNodeCode($startNode->getNodeCode())
            ->setNodeName($startNode->getNodeName())
            ->setNodeType($startNode->getNodeType());
        FlowEngine::dataFillHandler()->idFill($startTask);
        // 开始任务转历史任务
        return FlowEngine::hisTaskService()->setSkipInsHis($startTask, $nextNodes, $flowParams);
    }

    /**
     * 开启流程，保存流程信息
     *
     * @param IFlowInstanceDao $instance 流程实例
     * @param array $addTasks 新增任务
     * @param IFlowHisTaskDao $hisTask 历史任务
     * @param FlowParams $flowParams 流程参数
     * @return void
     */
    private function saveFlowInfo(IFlowInstanceDao $instance, array $addTasks, IFlowHisTaskDao $hisTask, FlowParams $flowParams): void
    {
        FlowEngine::taskService()->setInsFinishInfo($instance, $addTasks, $flowParams);
        // 待办任务设置处理人
        if (CollUtil::isNotEmpty($addTasks)) {
            $users = FlowEngine::userService()->taskAddUsers($addTasks);
            FlowEngine::taskService()->saveBatch($addTasks);
            FlowEngine::userService()->saveBatch($users);
        }


        FlowEngine::hisTaskService()->save($hisTask);
        $this->save($instance);
    }

    /**
     * 设置流程实例对象
     *
     * @param IFlowNodeDao $firstBetweenNode 第一个中间节点
     * @param string $businessId 业务 id
     * @param FlowParams $flowParams 流程参数
     * @return IFlowInstanceDao|null
     */
    private function setStartInstance(IFlowNodeDao $firstBetweenNode, string $businessId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        $instance = FlowEngine::newIns();
        $now      = date('Y-m-d H:i:s');
        FlowEngine::dataFillHandler()->idFill($instance);
        // 关联业务 id
        $instance->setDefinitionId($firstBetweenNode->getDefinitionId())
            ->setBusinessId($businessId)
            ->setNodeType($firstBetweenNode->getNodeType())
            ->setNodeCode($firstBetweenNode->getNodeCode())
            ->setNodeName($firstBetweenNode->getNodeName())
            ->setFlowStatus(StringUtils::emptyDefault($flowParams->getFlowStatus(), FlowStatus::TOBESUBMIT->value))
            ->setActivityStatus(ActivityStatus::ACTIVITY->value)
            ->setVariable(FlowEngine::$jsonConvert->objToStr($flowParams->getVariable()))
            ->setCreateTime($now)
            ->setUpdateTime($now)
            ->setCreateBy($flowParams->getHandler())
            ->setUpdateBy($flowParams->getHandler())
            ->setExt($flowParams->getExt());
        return $instance;
    }

    /**
     * 删除流程任务
     *
     * @param array $instanceIds 流程实例 id 集合
     * @return bool
     * @throws FlowException
     */
    private function toRemoveTask(array $instanceIds): bool
    {
        AssertUtil::isEmpty($instanceIds, ExceptionCons::NULL_INSTANCE_ID);

        $taskIds = [];
        foreach ($instanceIds as $instanceId) {
            /**
             * @var IFlowTaskDao[] $tasks
             */
            $tasks = FlowEngine::taskService()
                ->list(FlowEngine::newTask()->setInstanceId($instanceId));
            foreach ($tasks as $task) {
                $taskIds[] = $task->getId();
            }
        }

        if (CollUtil::isNotEmpty($taskIds)) {
            FlowEngine::userService()->deleteByTaskIds($taskIds);
        }

        FlowEngine::taskService()->deleteByInsIds($instanceIds);
        FlowEngine::hisTaskService()->deleteByInsIds($instanceIds);
        return FlowEngine::insService()->removeByIds($instanceIds);
    }
}
