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

use Yflow\core\dto\FlowParams;
use Yflow\core\enums\CooperateType;
use Yflow\core\enums\FlowStatus;
use Yflow\core\enums\SkipType;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\orm\dao\IFlowHisTaskDao;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\dao\IFlowUserDao;
use Yflow\core\orm\service\impl\WarmServiceImpl;
use Yflow\core\service\HisTaskService;
use Yflow\core\utils\ArrayUtil;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\StreamUtils;
use Yflow\core\utils\StringUtils;


/**
 * HisTaskServiceImpl - 历史任务记录 Service 业务层处理
 *
 *
 * @since 2023-03-29
 */
class HisTaskServiceImpl extends WarmServiceImpl implements HisTaskService
{
    public function __construct()
    {
        $this->setDao(FrameInvoker::getBean(IFlowHisTaskDao::class));
    }

    /**
     * 设置 DAO
     *
     * @param IFlowHisTaskDao $warmDao DAO
     * @return HisTaskService
     */
    public function setDao($warmDao): HisTaskService
    {
        $this->warmDao = $warmDao;
        return $this;
    }

    /**
     * 根据任务 id 查询历史任务
     *
     * @param int $taskId 任务 id
     * @return array<IFlowHisTaskDao>
     */
    public function listByTaskId(int $taskId): array
    {
        return $this->list(FlowEngine::newHisTask()->setTaskId($taskId));
    }

    /**
     * 根据任务 id 和协作类型查询
     *
     * @param int $taskId 任务 id
     * @param int ...$cooperateTypes 协作类型集合
     * @return array<IFlowHisTaskDao> 历史任务列表
     */
    public function listByTaskIdAndCooperateTypes(int $taskId, int ...$cooperateTypes): array
    {
        if (ArrayUtil::isEmpty($cooperateTypes)) {
            return $this->listByTaskId($taskId);
        }
        if (count($cooperateTypes) === 1) {
            return $this->list(FlowEngine::newHisTask()->setTaskId($taskId)->setCooperateType($cooperateTypes[0]));
        }
        /**
         * @var IFlowHisTaskDao $dao
         */
        $dao = $this->getDao();
        return $dao->listByTaskIdAndCooperateTypes($taskId, $cooperateTypes);
    }

    /**
     * 根据实例 Id 和节点编码查询
     *
     * @param int $instanceId 流程实例 id
     * @param array $nodeCodes 节点编码集合
     * @return array<IFlowHisTaskDao> 历史任务列表
     */
    public function getByInsAndNodeCodes(int $instanceId, array $nodeCodes): array
    {
        /**
         * @var IFlowHisTaskDao $dao
         */
        $dao = $this->getDao();
        return $dao->getByInsAndNodeCodes($instanceId, $nodeCodes);
    }

    /**
     * 根据 instanceIds 删除
     *
     * @param array $instanceIds 流程实例 id 集合
     * @return bool
     */
    public function deleteByInsIds(array $instanceIds): bool
    {
        /**
         * @var IFlowHisTaskDao $dao
         */
        $dao = $this->getDao();
        return $dao->deleteByInsIds($instanceIds);
    }

    /**
     * 设置流程历史任务信息
     *
     * @param IFlowTaskDao $task 当前任务
     * @param array $nextNodes 后续任务
     * @param FlowParams $flowParams 参数
     * @return IFlowHisTaskDao|null
     */
    public function setSkipInsHis(IFlowTaskDao $task, array $nextNodes, FlowParams $flowParams): ?IFlowHisTaskDao
    {
        $flowStatus = $this->getFlowStatus($flowParams);
        return $this->setSkipHis($task, $nextNodes, $flowParams, $flowStatus);
    }

    /**
     * 设置流程历史任务信息
     *
     * @param array $taskList 当前任务集合
     * @param array $nextNodes 后续任务
     * @param FlowParams $flowParams 参数
     * @return array<IFlowHisTaskDao>
     */
    public function setSkipHisList(array $taskList, array $nextNodes, FlowParams $flowParams): array
    {
        $flowStatus = $this->getFlowStatus($flowParams);
        $hisTasks   = [];
        foreach ($taskList as $task) {
            $hisTask    = $this->setSkipHis($task, $nextNodes, $flowParams, $flowStatus);
            $hisTasks[] = $hisTask;
        }
        return $hisTasks;
    }

    /**
     * 设置协作历史记录
     *
     * @param IFlowTaskDao $task 当前任务
     * @param IFlowNodeDao $node 当然任务节点
     * @param FlowParams $flowParams 参数
     * @param array $collaborators 协作人
     * @return IFlowHisTaskDao|null
     */
    public function setCooperateHis(IFlowTaskDao $task, IFlowNodeDao $node, FlowParams $flowParams, array $collaborators): ?IFlowHisTaskDao
    {
        $flowStatus = $this->getFlowStatus($flowParams);
        $hisTask    = FlowEngine::newHisTask()
            ->setTaskId($task->getId())
            ->setInstanceId($task->getInstanceId())
            ->setCooperateType(ObjectUtil::isNotNull($flowParams->getCooperateType())
                ? $flowParams->getCooperateType() : CooperateType::APPROVAL->value)
            ->setCollaborator(StreamUtils::join($collaborators, fn($c) => $c))
            ->setNodeCode($task->getNodeCode())
            ->setNodeName($task->getNodeName())
            ->setNodeType($task->getNodeType())
            ->setDefinitionId($task->getDefinitionId())
            ->setTargetNodeCode($node->getNodeCode())
            ->setTargetNodeName($node->getNodeName())
            ->setApprover($flowParams->getHandler())
            ->setSkipType($flowParams->getSkipType())
            ->setFlowStatus(StringUtils::isNotEmpty($flowStatus)
                ? $flowStatus : FlowStatus::APPROVAL->value)
            ->setFormCustom($task->getFormCustom())
            ->setFormPath($task->getFormPath())
            ->setMessage($flowParams->getMessage())
            ->setVariable($flowParams->getVariableStr())
            ->setExt($flowParams->getHisTaskExt())
            ->setCreateTime($task->getCreateTime());
        FlowEngine::dataFillHandler()->idFill($hisTask);
        return $hisTask;
    }

    /**
     * 委派历史任务
     *
     * @param IFlowTaskDao $task 当前任务
     * @param FlowParams $flowParams 参数
     * @param IFlowUserDao $entrustedUser 委托人
     * @return IFlowHisTaskDao|null
     */
    public function setDeputeHisTask(IFlowTaskDao $task, FlowParams $flowParams, IFlowUserDao $entrustedUser): ?IFlowHisTaskDao
    {
        $flowStatus = $this->getFlowStatus($flowParams);
        $hisTask    = FlowEngine::newHisTask()
            ->setTaskId($task->getId())
            ->setInstanceId($task->getInstanceId())
            ->setCooperateType(CooperateType::DEPUTE->value)
            ->setNodeCode($task->getNodeCode())
            ->setNodeName($task->getNodeName())
            ->setNodeType($task->getNodeType())
            ->setDefinitionId($task->getDefinitionId())
            ->setTargetNodeCode($task->getNodeCode())
            ->setTargetNodeName($task->getNodeName())
            ->setApprover($flowParams->getHandler())
            ->setCollaborator($entrustedUser->getCreateBy())
            ->setSkipType($flowParams->getSkipType())
            ->setFlowStatus((StringUtils::isNotEmpty($flowStatus) ? $flowStatus : SkipType::isReject($flowParams->getSkipType())) ? FlowStatus::REJECT->value : FlowStatus::PASS->value)
            ->setFormCustom($task->getFormCustom())
            ->setFormPath($task->getFormPath())
            ->setMessage($flowParams->getMessage())
            ->setVariable($flowParams->getVariableStr())
            ->setExt($flowParams->getHisTaskExt())
            ->setCreateTime($task->getCreateTime());
        FlowEngine::dataFillHandler()->idFill($hisTask);
        return $hisTask;
    }

    /**
     * 设置会签票签历史任务
     *
     * @param IFlowTaskDao $task 当前任务
     * @param FlowParams $flowParams 参数
     * @param string $nodeRatio 节点比率
     * @param bool $isPass 是否通过
     * @return IFlowHisTaskDao|null
     */
    public function setSignHisTask(IFlowTaskDao $task, FlowParams $flowParams, string $nodeRatio, bool $isPass): ?IFlowHisTaskDao
    {
        $flowStatus = $this->getFlowStatus($flowParams);
        $hisTask    = FlowEngine::newHisTask()
            ->setTaskId($task->getId())
            ->setInstanceId($task->getInstanceId())
            ->setCooperateType(CooperateType::isCountersign($nodeRatio)
                ? CooperateType::COUNTERSIGN->value : CooperateType::VOTE->value)
            ->setNodeCode($task->getNodeCode())
            ->setNodeName($task->getNodeName())
            ->setNodeType($task->getNodeType())
            ->setDefinitionId($task->getDefinitionId())
            ->setApprover($flowParams->getHandler())
            ->setMessage($flowParams->getMessage())
            ->setSkipType($isPass ? SkipType::PASS->value : SkipType::REJECT->value)
            ->setFlowStatus((StringUtils::isNotEmpty($flowStatus) ? $flowStatus : $isPass) ? FlowStatus::PASS->value : FlowStatus::REJECT->value)
            ->setFormCustom($task->getFormCustom())
            ->setFormPath($task->getFormPath())
            ->setVariable($flowParams->getVariableStr())
            ->setExt($flowParams->getHisTaskExt())
            ->setCreateTime($task->getCreateTime());
        FlowEngine::dataFillHandler()->idFill($hisTask);
        return $hisTask;
    }

    /**
     * 设置流程历史任务信息
     *
     * @param IFlowTaskDao $task 当前任务
     * @param IFlowNodeDao $nextNode 跳转的节点
     * @param FlowParams $flowParams 流程参数
     * @return IFlowHisTaskDao|null
     */
    public function setSkipHisTask(IFlowTaskDao $task, IFlowNodeDao $nextNode, FlowParams $flowParams): ?IFlowHisTaskDao
    {
        $flowStatus = $this->getFlowStatus($flowParams);
        return $this->setSkipHis($task, CollUtil::toList($nextNode), $flowParams, $flowStatus);
    }

    /**
     * 根据流程实例 id 查询历史任务
     *
     * @param int $instanceId 流程实例 id
     * @return array<IFlowHisTaskDao> 历史记录集合
     */
    public function getByInsId(int $instanceId): array
    {
        return FlowEngine::hisTaskService()->list(FlowEngine::newHisTask()->setInstanceId($instanceId));
    }

    /**
     * 设置跳过历史任务
     *
     * @param IFlowTaskDao $task 当前任务
     * @param array $nextNodes 下一节点集合
     * @param FlowParams $flowParams 流程参数
     * @param string|null $flowStatus 流程状态
     * @return IFlowHisTaskDao|null
     */
    private function setSkipHis(IFlowTaskDao $task, array $nextNodes, FlowParams $flowParams, ?string $flowStatus): ?IFlowHisTaskDao
    {
        $hisTask = FlowEngine::newHisTask()
            ->setTaskId($task->getId())
            ->setInstanceId($task->getInstanceId())
            ->setCooperateType(ObjectUtil::isNotNull($flowParams->getCooperateType())
                ? $flowParams->getCooperateType() : CooperateType::APPROVAL->value)
            ->setNodeCode($task->getNodeCode())
            ->setNodeName($task->getNodeName())
            ->setNodeType($task->getNodeType())
            ->setDefinitionId($task->getDefinitionId())
            ->setTargetNodeCode(StreamUtils::join($nextNodes, fn($n) => $n->getNodeCode()))
            ->setTargetNodeName(StreamUtils::join($nextNodes, fn($n) => $n->getNodeName()))
            ->setApprover($flowParams->getHandler())
            ->setSkipType($flowParams->getSkipType())
            ->setFlowStatus((StringUtils::isNotEmpty($flowStatus) ? $flowStatus : SkipType::isReject($flowParams->getSkipType())) ? FlowStatus::REJECT->value : FlowStatus::PASS->value)
            ->setFormCustom($task->getFormCustom())
            ->setFormPath($task->getFormPath())
            ->setMessage($flowParams->getMessage())
            ->setVariable($flowParams->getVariableStr())
            ->setExt($flowParams->getHisTaskExt())
            ->setCreateTime($task->getCreateTime());
        FlowEngine::dataFillHandler()->idFill($hisTask);
        return $hisTask;
    }

    /**
     * 获取流程状态
     *
     * @param FlowParams $flowParams 流程参数
     * @return string|null
     */
    private function getFlowStatus(FlowParams $flowParams): ?string
    {
        return StringUtils::emptyDefault($flowParams->getHisStatus(), $flowParams->getFlowStatus());
    }
}
