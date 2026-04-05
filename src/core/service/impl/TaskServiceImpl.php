<?php
declare(strict_types=1);
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
use Yflow\core\constant\FlowCons;
use Yflow\core\dto\DefJson;
use Yflow\core\dto\FlowCombine;
use Yflow\core\dto\FlowDto;
use Yflow\core\dto\FlowParams;
use Yflow\core\dto\PathWayData;
use Yflow\core\enums\ActivityStatus;
use Yflow\core\enums\CooperateType;
use Yflow\core\enums\FlowStatus;
use Yflow\core\enums\NodeType;
use Yflow\core\enums\SkipType;
use Yflow\core\enums\UserType;
use Yflow\core\exception\FlowException;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;
use Yflow\core\orm\dao\IFlowDefinitionDao;
use Yflow\core\orm\dao\IFlowFormDao;
use Yflow\core\orm\dao\IFlowHisTaskDao;
use Yflow\core\orm\dao\IFlowInstanceDao;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\service\impl\WarmServiceImpl;
use Yflow\core\service\TaskService;
use Yflow\core\utils\AssertUtil;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ExpressionUtil;
use Yflow\core\utils\ListenerUtil;
use Yflow\core\utils\MapUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\SqlHelper;
use Yflow\core\utils\StreamUtils;
use Yflow\core\utils\StringUtils;


/**
 * TaskServiceImpl - 待办任务 Service 业务层处理
 *
 *
 * @since 2023-03-29
 */
class TaskServiceImpl extends WarmServiceImpl implements TaskService
{

    public function __construct()
    {
        $this->setDao(FrameInvoker::getBean(IFlowTaskDao::class));
    }

    /**
     * 设置 DAO
     *
     * @param IFlowTaskDao $warmDao DAO
     * @return TaskService
     */
    public function setDao($warmDao): TaskService
    {
        $this->warmDao = $warmDao;
        return $this;
    }

    /**
     * 流程通过
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function pass(int $taskId, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao
    {
        return $this->skip($taskId, FlowParams::build()
            ->skipType(SkipType::PASS->value)
            ->message($message)
            ->variable($variable));
    }

    /**
     * 根据任务 id，流程跳转
     *
     * @param int $taskId 流程任务 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function skip(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        /**
         * @var IFlowTaskDao $task
         */
        $task = $this->getById($taskId);
        return $this->skipByFlowParams($flowParams, $task);
    }

    /**
     * 根据任务 id，流程跳转
     *
     * @param FlowParams $flowParams 包含流程相关参数
     * @param IFlowTaskDao $task 流程任务 [必传]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     * @throws Exception
     */
    public function skipByFlowParams(FlowParams $flowParams, IFlowTaskDao $task): ?IFlowInstanceDao
    {
        // TODO min 后续考虑并发问题，待办任务和实例表不同步，可给待办任务 id 加锁，抽取所接口，方便后续兼容分布式锁
        // 流程开启前正确性校验
        $r        = $this->getAndCheckByTaskObj($task);
        $variable = MapUtil::mergeAll($r->instance->getVariableMap(), $flowParams->getVariable());
        $flowParams->variable($variable);
        // 非第一个记得跳转类型必传
        if (!NodeType::isStart($task->getNodeType())) {
            AssertUtil::isFalse(StringUtils::isNotEmpty($flowParams->getSkipType()), ExceptionCons::NULL_CONDITION_VALUE);
        }
        $task->setUserList(FlowEngine::userService()->listByAssociatedAndTypes($task->getId()));
        $flowCombine = FlowEngine::defService()->getFlowCombineNoDef($r->definition->getId());

        // 执行开始监听器
        ListenerUtil::executeListener((new ListenerVariable($r->definition, $r->instance, $r->nowNode,
            $flowParams->getVariable(), $task))->setFlowParams($flowParams), Listener::LISTENER_START);

        // 如果是受托人在处理任务，需要处理一条委派记录，并且更新委托人，回到计划审批人，然后直接返回流程实例
        if (!$flowParams->isIgnoreDepute() && $this->handleDepute($task, $flowParams)) {
            return $r->instance;
        }

        // 判断当前处理人是否有权限处理
        $this->checkAuth($task, $flowParams);

        //或签、会签、票签逻辑处理
        if (!$flowParams->isIgnoreCooperate() && $this->cooperate($r->nowNode, $task, $flowParams)) {
            return $r->instance;
        }

        // 获取后续任务节点结合
        $pathWayData = new PathWayData();
        $pathWayData->setInsId($task->getInstanceId())->setSkipType($flowParams->getSkipType());
        $nextNode  = FlowEngine::nodeService()->getNextNodeByNode($r->nowNode, $flowParams->getNodeCode()
            , $flowParams->getSkipType(), $pathWayData, $flowCombine);
        $nextNodes = FlowEngine::nodeService()->getNextByCheckGateway($flowParams->getVariable()
            , $nextNode, $pathWayData, $flowCombine);

        // 判断并行网关和包容网关节点只剩一个前置代办任务，才能生成新的代办任务
        $this->isGenerateNewTask($pathWayData, $r->instance, $nextNodes);
        $targetNodes = $pathWayData->getTargetNodes();
        $pathWayData->setTargetNodes(array_merge($targetNodes, $nextNodes));

        // 设置流程图元数据
        $r->instance->setDefJson(FlowEngine::chartService()->skipMetadata($pathWayData));

        // 构建增待办任务和设置结束任务历史记录
        $addTasks = StreamUtils::toList($nextNodes, fn($node) => $this->addTask($node, $r->instance, $r->definition, $flowParams));

        // 办理人变量替换
        try {
            ExpressionUtil::evalVariable($addTasks, $flowParams->variable(MapUtil::mergeAll($r->instance->getVariableMap(), $flowParams->getVariable())));
        } catch (Exception $e) {
            throw new FlowException($e->getMessage());
        }

        // 执行分派监听器
        ListenerUtil::executeListener((new ListenerVariable($r->definition, $r->instance, $r->nowNode,
            $flowParams->getVariable(), $task, $nextNodes, $addTasks))->setFlowParams($flowParams), Listener::LISTENER_ASSIGNMENT);

        // 更新流程信息
        $this->updateFlowInfo($task, $r->instance, $addTasks, $flowParams, $nextNodes);

        // 一票否决（谨慎使用），如果退回，退回指向节点后还存在其他正在执行的待办任务，转历史任务，状态都为失效，重走流程。
        if (CollUtil::isNotEmpty($nextNodes) && SkipType::isReject($flowParams->getSkipType())) {
            $this->oneVoteVeto($task, $nextNodes[0]->getNodeCode(), $flowCombine);
        }

        // 处理未完成的任务，当流程完成，还存在待办任务未完成，转历史任务，状态完成。
        $this->handUndoneTask($r->instance);

        // 执行完成和创建监听器
        ListenerUtil::endCreateListener((new ListenerVariable($r->definition, $r->instance, $r->nowNode,
            $flowParams->getVariable(), $task, $nextNodes, $addTasks))->setFlowParams($flowParams));

        return $r->instance;
    }

    /**
     * 获取并检查
     *
     * @param IFlowTaskDao|null $task 任务
     * @return TaskCheckResult
     * @throws FlowException
     */
    private function getAndCheckByTaskObj(?IFlowTaskDao $task): TaskCheckResult
    {
        AssertUtil::isNull($task, ExceptionCons::NOT_FOUNT_TASK);
        /**
         * @var IFlowInstanceDao $instance
         */
        $instance = FlowEngine::insService()->getById($task->getInstanceId());
        AssertUtil::isNull($instance, ExceptionCons::NOT_FOUNT_INSTANCE);
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = FlowEngine::defService()->getById($instance->getDefinitionId());
        AssertUtil::isFalse($this->judgeActivityStatus($definition, $instance), ExceptionCons::NOT_ACTIVITY);
        AssertUtil::isTrue(NodeType::isEnd($instance->getNodeType()), ExceptionCons::FLOW_FINISH);
        $nowNode = FlowEngine::nodeService()->getByDefIdAndNodeCode($task->getDefinitionId(), $task->getNodeCode());
        AssertUtil::isNull($nowNode, ExceptionCons::LOST_CUR_NODE);
        return new TaskCheckResult($instance, $nowNode, $task, $definition);
//        $r->instance = $instance;
//        $r->nowNode = $nowNode;
//        $r->task = $task;
//        $r->definition = $definition;
//        return $r;
    }

    /**
     * 判断活动状态
     *
     * @param IFlowDefinitionDao $definition 流程定义
     * @param IFlowInstanceDao $instance 流程实例
     * @return bool
     */
    private function judgeActivityStatus(IFlowDefinitionDao $definition, IFlowInstanceDao $instance): bool
    {
        return ActivityStatus::isActivity($definition->getActivityStatus())
            && ActivityStatus::isActivity($instance->getActivityStatus());
    }

    /**
     * 处理委派
     *
     * @param IFlowTaskDao $task 任务
     * @param FlowParams $flowParams 流程参数
     * @return bool
     */
    private function handleDepute(IFlowTaskDao $task, FlowParams $flowParams): bool
    {
        // 获取受托人
        $entrustedUserList = StreamUtils::filter($task->getUserList(),
            fn($user) => UserType::DEPUTE === $user->getType()
                && $flowParams->getHandler() === $user->getProcessedBy());
        if (CollUtil::isEmpty($entrustedUserList)) {
            return false;
        }

        // 记录受托人处理任务记录
        $entrustedUser = $entrustedUserList[0];
        $hisTask       = FlowEngine::hisTaskService()->setDeputeHisTask($task, $flowParams, $entrustedUser);
        FlowEngine::hisTaskService()->save($hisTask);
        FlowEngine::userService()->removeById($entrustedUser->getId());

        // 查询委托人，如果在 flow_user 不存在，则给委托人新增待办记录
        $deputeUser = FlowEngine::userService()->getOne(FlowEngine::newUser()->setAssociated($task->getId())
            ->setProcessedBy($entrustedUser->getCreateBy())->setType(UserType::APPROVAL->value));
        if (ObjectUtil::isNull($deputeUser)) {
            $newUser = FlowEngine::userService()->structureUserWithHandler($entrustedUser->getAssociated()
                , $entrustedUser->getCreateBy()
                , UserType::APPROVAL->value, $entrustedUser->getProcessedBy());
            FlowEngine::userService()->save($newUser);
        }

        return true;
    }

    /**
     * 判断当前处理人是否有权限处理
     *
     * @param IFlowTaskDao $task 当前任务（任务 id）
     * @param FlowParams $flowParams :包含流程相关参数的对象
     * @return void
     * @throws FlowException
     */
    private function checkAuth(IFlowTaskDao $task, FlowParams $flowParams): void
    {
        if ($flowParams->isIgnore()) {
            return;
        }
        // 查询审批人和转办人
        $permissions = StreamUtils::toList($task->getUserList(), fn($user) => $user->getProcessedBy());
        // 当前办理人的权限和设计时候填的权限集合是否有交集，有说明有权限办理
        AssertUtil::isTrue(CollUtil::isNotEmpty($permissions) && (CollUtil::isEmpty($flowParams->getPermissionFlag())
                || CollUtil::notContainsAny($flowParams->getPermissionFlag(), $permissions)), ExceptionCons::NULL_ROLE_NODE);
    }

    /**
     * 会签，票签，协作处理，返回 true；或签或者会签、票签结束返回 false
     *
     * @param IFlowNodeDao $nowNode 当前节点
     * @param IFlowTaskDao $task 任务
     * @param FlowParams $flowParams 流程参数
     * @return bool
     * @throws FlowException
     */
    private function cooperate(IFlowNodeDao $nowNode, IFlowTaskDao $task, FlowParams $flowParams): bool
    {
        $nodeRatio = $nowNode->getNodeRatio();
        // 或签，直接返回
        if (CooperateType::isOrSign($nodeRatio)) {
            return false;
        }

        // 办理人和转办人列表
        $todoList = FlowEngine::userService()->listByAssociatedAndTypes($task->getId()
            , UserType::APPROVAL->value, UserType::TRANSFER->value, UserType::DEPUTE->value);

        // 判断办理人是否有办理权限
        AssertUtil::isEmpty($flowParams->getHandler(), ExceptionCons::SIGN_NULL_HANDLER);
        $todoUser = CollUtil::getOne(StreamUtils::filter($todoList, fn($u) => $u->getProcessedBy() === $flowParams->getHandler()));
        AssertUtil::isNull($todoUser, ExceptionCons::NOT_AUTHORITY);

        // 除当前办理人外剩余办理人列表
        $restList = StreamUtils::filter($todoList, fn($u) => $u->getProcessedBy() !== $flowParams->getHandler());

        // 会签并且当前人退回直接返回
        if (CooperateType::isCountersign($nodeRatio) && SkipType::isReject($flowParams->getSkipType())) {
            return $this->removeRestList($restList);
        }

        // 查询会签票签已办列表
        $doneList = FlowEngine::hisTaskService()->listByTaskId($task->getId());
        $doneList = CollUtil::emptyDefault($doneList);

        // 总人数
        $allNum = count($todoList) + count($doneList);

        // 通过历史记录
        $donePassList = StreamUtils::filter($doneList
            , fn($hisTask) => $hisTask->getSkipType() === SkipType::PASS);

        // 驳回历史记录
        $doneRejectList = StreamUtils::filter($doneList
            , fn($hisTask) => $hisTask->getSkipType() === SkipType::REJECT);

        $isPass = SkipType::isPass($flowParams->getSkipType());
        // 如果是票签默认或者 spel 表达式策略，则执行表达式
        if (CooperateType::isVoteSignDefault($nodeRatio) || CooperateType::isVoteSignRejectSpel($nodeRatio)) {
            $variable               = MapUtil::clone($flowParams->getVariable());
            $variable['skipType']   = $flowParams->getSkipType();
            $variable['passNum']    = count($donePassList);
            $variable['rejectNum']  = count($doneRejectList);
            $variable['todoNum']    = count($todoList);
            $variable['allNum']     = $allNum;
            $variable['passList']   = $donePassList;
            $variable['rejectList'] = $doneRejectList;
            $variable['todoList']   = $todoList;
            try {
                if (ExpressionUtil::evalVoteSign($nodeRatio, $variable)) {
                    return $this->removeRestList($restList);
                }
            } catch (Exception $e) {
                throw new FlowException($e->getMessage());
            }
        } else {
            // 计算通过率
            $passRatio = ($isPass ? 1 : 0)
                + count($donePassList);
            $passRatio = bcdiv((string)$passRatio, (string)$allNum, 4) * 100;
            // 计算驳回率
            $rejectRatio = (!$isPass ? 1 : 0)
                + count($doneRejectList);
            $rejectRatio = bcdiv((string)$rejectRatio, (string)$allNum, 4) * 100;

            // 判断是否是票签中的固定通过人数，如果是则判断是否达到该人数
            if (CooperateType::isVoteSignPassCount($nodeRatio)) {
                $passCount = intval(substr($nodeRatio, strpos($nodeRatio, "=") + 1));
                if (($isPass && count($donePassList) + 1 >= $passCount)
                    || (!$isPass && count($doneRejectList) + 1 > $allNum - $passCount)) {
                    return $this->removeRestList($restList);
                }
            } elseif (CooperateType::isVoteSignRejectCount($nodeRatio)) {
                // 判断是否是票签中的固定驳回人数，如果是则判断是否达到该人数
                $rejectCount = intval(substr($nodeRatio, strpos($nodeRatio, "=") + 1));
                if ((!$isPass && count($doneRejectList) + 1 >= $rejectCount)
                    || ($isPass && count($donePassList) + 1 > $allNum - $rejectCount)) {
                    return $this->removeRestList($restList);
                }
            } elseif ((!$isPass && $rejectRatio > (100 - floatval($nodeRatio)))
                || ($isPass && $passRatio >= floatval($nodeRatio))) {
                // 提前不满足通过率或者满足通过率，删除剩余办理人，流程正常流程流转
                return $this->removeRestList($restList);
            }
        }

        // 当只剩一位待办用户时，由当前用户决定走向
        if (count($todoList) === 1) {
            return false;
        }

        // 添加历史任务
        $hisTask = FlowEngine::hisTaskService()->setSignHisTask($task, $flowParams, $nodeRatio, $isPass);
        FlowEngine::hisTaskService()->save($hisTask);

        // 删掉待办用户
        FlowEngine::userService()->removeById($todoUser->getId());
        return true;
    }

    /**
     * 删除剩余办理人
     * @param array $restList 待办用户列表
     * @return  bool
     */
    private static function removeRestList(array $restList): bool
    {
        if (CollUtil::isNotEmpty($restList)) {
            FlowEngine::userService()->removeByIds(StreamUtils::toList($restList, fn($user) => $user->getId()));
        }
        return false;
    }

    /**
     * 判断并行网关和包容网关节点只剩一个前置代办任务，才能生成新的代办任务
     *
     * @param PathWayData $pathWayData 办理过程中途径数据
     * @param IFlowInstanceDao $instance 实例
     * @param array $nextNodes 下一节点集合
     * @return void
     */
    private function isGenerateNewTask(PathWayData $pathWayData, IFlowInstanceDao $instance, array &$nextNodes): void
    {
        if (SkipType::isReject($pathWayData->getSkipType())) {
            return;
        }
        /**
         * @var DefJson $defJson
         */
        $defJson     = FlowEngine::$jsonConvert->strToBean($instance->getDefJson(), DefJson::class);
        $nodeJsonMap = StreamUtils::toMap($defJson->getNodeList(), fn($node) => $node->getNodeCode(), fn($node) => $node);
        // 遍历目标节点，获取目标节点中第一个互斥或者包含网关，并且判断只剩一个前置代办任务，才能生成新的代办任务
        $parallelOrInclusiveList = [];
        foreach ($pathWayData->getPathWayNodes() as $node) {
            if (NodeType::isGateWayParallel($node->getNodeType()) || NodeType::isGateWayInclusive($node->getNodeType())) {
                $parallelOrInclusiveList[] = $node;
            }
        }

        if (CollUtil::isNotEmpty($parallelOrInclusiveList)) {
            $previousNodeList = FlowEngine::nodeService()->previousNodeListByDefId($instance->getDefinitionId()
                , $parallelOrInclusiveList[count($parallelOrInclusiveList) - 1]->getNodeCode());
            // 获取前置节点中代办节点的数量
            $statusOneCount = 0;
            foreach ($previousNodeList as $node) {
                $nodeJson = $nodeJsonMap[$node->getNodeCode()] ?? null;
                if ($nodeJson !== null && $nodeJson->getStatus() == 1) {
                    $statusOneCount++;
                }
            }
            // 并行网关和包容网关节点超过一个前置代办任务，说明可以不可生成新任务，
            if ($statusOneCount > 1) {
                $nextNodes    = [];
                $flag         = false;
                $pathWayNodes = [];
                foreach ($pathWayData->getPathWayNodes() as $nodeJson) {
                    if ($nodeJson->getNodeCode() === $parallelOrInclusiveList[0]->getNodeCode()) {
                        $flag = true;
                    }
                    if ($flag) {
                        $pathWayNodes[] = $nodeJson;
                    }
                }
                $pathWayData->setPathWayNodes($pathWayNodes);

                $flag         = false;
                $pathWaySkips = [];
                foreach ($pathWayData->getPathWaySkips() as $skip) {
                    if ($skip->getNowNodeCode() === $parallelOrInclusiveList[0]->getNodeCode()) {
                        $flag = true;
                    }
                    if ($flag) {
                        $pathWaySkips[] = $skip;
                    }
                }
                $pathWayData->setPathWaySkips($pathWaySkips);
            }
        }
    }

    /**
     * 设置流程待办任务对象
     *
     * @param IFlowNodeDao $node 节点
     * @param IFlowInstanceDao $instance 流程实例
     * @param IFlowDefinitionDao $definition 流程定义
     * @param FlowParams $flowParams 流程参数
     * @return IFlowTaskDao|null
     */
    public function addTask(IFlowNodeDao $node, IFlowInstanceDao $instance, IFlowDefinitionDao $definition, FlowParams $flowParams): ?IFlowTaskDao
    {
        $addTask = FlowEngine::newTask();
        $now     = date('Y-m-d H:i:s');
        FlowEngine::dataFillHandler()->idFill($addTask);
        $addTask->setDefinitionId($instance->getDefinitionId())
            ->setInstanceId($instance->getId())
            ->setNodeCode($node->getNodeCode())
            ->setNodeName($node->getNodeName())
            ->setNodeType($node->getNodeType())
            ->setFlowStatus(StringUtils::emptyDefault($flowParams->getFlowStatus(),
                $this->setFlowStatus($node->getNodeType(), $flowParams->getSkipType())))
            ->setCreateTime($now)
            ->setPermissionList(StringUtils::str2List($node->getPermissionFlag(), FlowCons::SPLIT_AT));

        if (StringUtils::isNotEmpty($node->getFormCustom()) && StringUtils::isNotEmpty($node->getFormPath())) {
            // 节点有自定义表单则使用
            $addTask->setFormCustom($node->getFormCustom())->setFormPath($node->getFormPath());
        } else {
            $addTask->setFormCustom($definition->getFormCustom())->setFormPath($definition->getFormPath());
        }

        return $addTask;
    }

    /**
     * 设置流程状态
     *
     * @param int $nodeType 节点类型
     * @param string $skipType 跳转类型
     * @return string 流程状态
     */
    private function setFlowStatus(int $nodeType, string $skipType): string
    {
        // 根据审批动作确定流程状态
        if (NodeType::isStart($nodeType)) {
            return FlowStatus::TOBESUBMIT->value;
        } elseif (NodeType::isEnd($nodeType)) {
            return FlowStatus::FINISHED->value;
        } elseif (SkipType::isReject($skipType)) {
            return FlowStatus::REJECT->value;
        } else {
            return FlowStatus::APPROVAL->value;
        }
    }

    /**
     * 更新流程信息
     *
     * @param IFlowTaskDao $task 当前任务
     * @param IFlowInstanceDao $instance 流程实例
     * @param array $addTasks 新增待办任务
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @param array $nextNodes 下一个节点集合
     * @return void
     */
    private function updateFlowInfo(IFlowTaskDao $task, IFlowInstanceDao $instance, array $addTasks, FlowParams $flowParams
        , array                                  $nextNodes): void
    {
        // 设置流程历史任务信息
        $insHis = FlowEngine::hisTaskService()->setSkipInsHis($task, $nextNodes, $flowParams);
        FlowEngine::hisTaskService()->save($insHis);
        $this->removeAndUser([$task]);
        // 待办任务设置处理人
        $users = FlowEngine::userService()->taskAddUsers($addTasks);

        // 设置任务完成后的实例相关信息
        $this->setInsFinishInfo($instance, $addTasks, $flowParams);
        if (CollUtil::isNotEmpty($addTasks)) {
            $this->saveBatch($addTasks);
        }
        FlowEngine::insService()->updateById($instance);
        // 保存下一个待办任务的权限人
        FlowEngine::userService()->saveBatch($users);
    }

    /**
     * 删除任务和用户
     *
     * @param array|null $taskList 任务列表
     * @return void
     */
    private function removeAndUser(?array $taskList): void
    {
        $this->removeByIds(StreamUtils::toList($taskList, fn($task) => $task->getId()));
        FlowEngine::userService()->deleteByTaskIds(StreamUtils::toList($taskList, fn($task) => $task->getId()));
    }

    /**
     * 设置任务完成后的实例相关信息
     *
     * @param IFlowInstanceDao $instance 实例对象
     * @param array $addTasks 新增待办任务
     * @param FlowParams $flowParams 流程参数对象
     * @return void
     */
    public function setInsFinishInfo(IFlowInstanceDao $instance, array $addTasks, FlowParams $flowParams): void
    {
        $instance->setUpdateTime(date('Y-m-d H:i:s'));
        // 合并流程变量到实例对象
        $this->mergeVariable($instance, $flowParams->getVariable());
        if (CollUtil::isNotEmpty($addTasks)) {
            $finallyTask = null;
            $addTasks    = array_filter($addTasks, function ($addTask) use (&$finallyTask) {
                if (NodeType::isEnd($addTask->getNodeType())) {
                    $finallyTask = $addTask;
                    return false;
                }
                return true;
            });
            if ($finallyTask === null) {
                $finallyTask = $this->getNextTask($addTasks);
            }
            $instance->setNodeType($finallyTask->getNodeType())
                ->setNodeCode($finallyTask->getNodeCode())
                ->setNodeName($finallyTask->getNodeName())
                ->setFlowStatus($finallyTask->getFlowStatus());
        }
    }

    /**
     * 合并流程变量到实例对象
     *
     * @param IFlowInstanceDao $instance 流程实例
     * @param array|null $variable 流程变量
     * @return void
     */
    public function mergeVariable(IFlowInstanceDao $instance, ?array $variable): void
    {
        if (MapUtil::isNotEmpty($variable)) {
            $variableStr = $instance->getVariable();
            $deserialize = FlowEngine::$jsonConvert->strToMap($variableStr);
            $deserialize = array_merge($deserialize, $variable);
            $instance->setVariable(FlowEngine::$jsonConvert->objToStr($deserialize));
        }
    }

    /**
     * 获取下一个任务
     *
     * @param array $tasks 任务列表
     * @return IFlowTaskDao|null
     */
    private function getNextTask(array $tasks): ?IFlowTaskDao
    {
        if (count($tasks) === 1) {
            return $tasks[0];
        }
        foreach ($tasks as $task) {
            if (NodeType::isEnd($task->getNodeType())) {
                return $task;
            }
        }
        return CollUtil::maxBy($tasks, fn($task) => $task->getId());
    }

    /**
     * 一票否决（谨慎使用），如果退回，退回指向节点后还存在其他正在执行的待办任务，转历史任务，状态都为退回，重走流程。
     *
     * @param IFlowTaskDao $task 当前任务
     * @param string $nextNodeCode 下一个节点编码
     * @param FlowCombine $flowCombine 流程数据集合
     * @return void
     */
    private function oneVoteVeto(IFlowTaskDao $task, string $nextNodeCode, FlowCombine $flowCombine): void
    {
        // 一票否决（谨慎使用），如果退回，退回指向节点后还存在其他正在执行的待办任务，转历史任务，状态失效，重走流程。
        $tasks = $this->list(FlowEngine::newTask()->setInstanceId($task->getInstanceId()));
        // 属于退回指向节点的后置未完成的任务
        $noDoneTasks    = [];
        $suffixNodeList = FlowEngine::nodeService()->suffixNodeListByFlowCombine($nextNodeCode, $flowCombine);
        $suffixCodes    = StreamUtils::toList($suffixNodeList, fn($node) => $node->getNodeCode());
        foreach ($tasks as $flowTask) {
            if (in_array($flowTask->getNodeCode(), $suffixCodes, true)) {
                $noDoneTasks[] = $flowTask;
            }
        }
        if (CollUtil::isNotEmpty($noDoneTasks)) {
            $this->removeAndUser($noDoneTasks);
        }
    }

    /**
     * 处理未完成的任务，当流程完成，还存在待办任务未完成，转历史任务，状态完成。
     *
     * @param IFlowInstanceDao $instance 流程实例
     * @return void
     */
    private function handUndoneTask(IFlowInstanceDao $instance): void
    {
        if (NodeType::isEnd($instance->getNodeType())) {
            $taskList = $this->list(FlowEngine::newTask()->setInstanceId($instance->getId()));
            if (CollUtil::isNotEmpty($taskList)) {
                $this->removeAndUser($taskList);
            }
        }
    }

    /**
     * 流程任意通过
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string $nodeCode 如果指定节点，可 [任意跳转] 到对应节点 [必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function passAtWill(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao
    {
        $flowParams = FlowParams::build()
            ->nodeCode($nodeCode)
            ->skipType(SkipType::PASS->value)
            ->message($message)
            ->variable($variable);

        return $this->skip($taskId, $flowParams);
    }

    /**
     * 流程通过，并且自定义流程状态
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @param string|null $flowStatus 流程状态，自定义流程状态 [按需传输]
     * @param string|null $hisStatus 历史任务表状态，自定义流程状态 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function passWithStatus(int $taskId, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao
    {
        $flowParams = FlowParams::build()
            ->skipType(SkipType::PASS->value)
            ->message($message)
            ->variable($variable)
            ->flowStatus($flowStatus)
            ->hisStatus($hisStatus);
        return $this->skip($taskId, $flowParams);
    }

    /**
     * 流程任意通过，并且自定义流程状态
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string $nodeCode 如果指定节点，可 [任意跳转] 到对应节点 [必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @param string|null $flowStatus 流程状态，自定义流程状态 [按需传输]
     * @param string|null $hisStatus 历史任务表状态，自定义流程状态 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function passAtWillWithStatus(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao
    {
        return $this->skip($taskId, FlowParams::build()
            ->nodeCode($nodeCode)
            ->skipType(SkipType::PASS->value)
            ->message($message)
            ->variable($variable)
            ->flowStatus($flowStatus)
            ->hisStatus($hisStatus));
    }

    /**
     * 流程退回
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function reject(int $taskId, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao
    {
        return $this->skip($taskId, FlowParams::build()
            ->skipType(SkipType::REJECT->value)
            ->message($message)
            ->variable($variable));
    }

    /**
     * 流程任意退回
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string $nodeCode 如果指定节点，可 [任意跳转] 到对应节点，严禁任意退回选择后置节点 [必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function rejectAtWill(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null): ?IFlowInstanceDao
    {
        return $this->skip($taskId, FlowParams::build()
            ->nodeCode($nodeCode)
            ->skipType(SkipType::REJECT->value)
            ->message($message)
            ->variable($variable));
    }

    /**
     * 流程退回，并且自定义流程状态
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @param string|null $flowStatus 流程状态，自定义流程状态 [按需传输]
     * @param string|null $hisStatus 历史任务表状态，自定义流程状态 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function rejectWithStatus(int $taskId, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao
    {
        return $this->skip($taskId, FlowParams::build()
            ->skipType(SkipType::REJECT->value)
            ->message($message)
            ->variable($variable)
            ->flowStatus($flowStatus)
            ->hisStatus($hisStatus));
    }

    /**
     * 流程任意退回，并且自定义流程状态
     *
     * @param int $taskId 流程任务 id[必传]
     * @param string $nodeCode 如果指定节点，可 [任意跳转] 到对应节点，严禁任意退回选择后置节点 [必传]
     * @param string|null $message 审批意见 [按需传输]
     * @param array|null $variable 流程变量 [按需传输]
     * @param string|null $flowStatus 流程状态，自定义流程状态 [按需传输]
     * @param string|null $hisStatus 历史任务表状态，自定义流程状态 [按需传输]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function rejectAtWillWithStatus(int $taskId, string $nodeCode, ?string $message = null, ?array $variable = null, ?string $flowStatus = null, ?string $hisStatus = null): ?IFlowInstanceDao
    {
        return $this->skip($taskId, FlowParams::build()
            ->nodeCode($nodeCode)
            ->skipType(SkipType::REJECT->value)
            ->message($message)
            ->variable($variable)
            ->flowStatus($flowStatus)
            ->hisStatus($hisStatus));
    }

    /**
     * 根据实例 id，流程跳转
     *
     * @param int $instanceId 流程实例 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function skipByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        return $this->skipByFlowParams($flowParams, $this->getTask($instanceId));
    }

    /**
     * 获取待办任务
     *
     * @param int $instanceId 实例 id
     * @return IFlowTaskDao|null 待办任务
     * @throws FlowException
     */
    private function getTask(int $instanceId): ?IFlowTaskDao
    {
        $taskList = $this->getByInsId($instanceId);
        AssertUtil::isEmpty($taskList, ExceptionCons::NOT_FOUNT_TASK);
        AssertUtil::isTrue(count($taskList) > 1, ExceptionCons::TASK_NOT_ONE);
        return $taskList[0];
    }

    /**
     * 根据流程实例 id 获取流程任务集合
     *
     * @param int $instanceId 流程实例 id
     * @return array 任务集合
     */
    public function getByInsId(int $instanceId): array
    {
        return $this->list(FlowEngine::newTask()->setInstanceId($instanceId));
    }

    /**
     * 驳回上一个任务
     *
     * @param int $instanceId 流程实例 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function rejectLastByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        return $this->rejectLastByTaskObj($this->getTask($instanceId), $flowParams);
    }

    /**
     * 驳回上一个任务
     *
     * @param IFlowTaskDao $task 流程任务 [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    private function rejectLastByTaskObj(IFlowTaskDao $task, FlowParams $flowParams): ?IFlowInstanceDao
    {
        $flowParams->skipType(SkipType::REJECT->value);
        AssertUtil::isNull($task, ExceptionCons::NOT_FOUNT_TASK);
        // 获取当前任务的前置任务
        $hisTaskList = FlowEngine::hisTaskService()->getByInsId($task->getInstanceId());
        // 获取 hisTaskList 中 TargetNodeCod 等于 task.getNodeCode() 的，并且 id 最大的
        $lastHisTask = null;
        foreach ($hisTaskList as $hisTask) {
            if (StringUtils::isNotEmpty($hisTask->getTargetNodeCode())
                && SkipType::isPass($hisTask->getSkipType())) {
                $targetCode = $hisTask->getTargetNodeCode();
                if (str_contains($targetCode, ',')) {
                    $targetCodes = explode(',', $targetCode);
                    $matches     = in_array($task->getNodeCode(), $targetCodes);
                } else {
                    $matches = $targetCode === $task->getNodeCode();
                }

                if ($matches) {
                    if ($lastHisTask === null || $hisTask->getId() > $lastHisTask->getId()) {
                        $lastHisTask = $hisTask;
                    }
                }
            }
        }

        AssertUtil::isNull($lastHisTask, ExceptionCons::NOT_FOUNT_LAST_TASK);
        $flowParams->nodeCode($lastHisTask->getNodeCode());
        return $this->skipByFlowParams($flowParams, $task);
    }

    /**
     * 驳回上一个任务
     *
     * @param int $taskId 流程任务 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function rejectLast(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        /**
         * @var IFlowTaskDao $task
         */
        $task = $this->getById($taskId);
        AssertUtil::isNull($task, ExceptionCons::NOT_FOUNT_TASK);
        return $this->rejectLastByTaskObj($task, $flowParams);
    }

    /**
     * 驳回上一个任务
     *
     * @param IFlowTaskDao $task 流程任务 [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function rejectLastByTask(IFlowTaskDao $task, FlowParams $flowParams): ?IFlowInstanceDao
    {
        return $this->rejectLastByTaskObj($task, $flowParams);
    }

    /**
     * 根据流程参数和任务进行流程跳转
     *
     * @param FlowParams $flowParams 包含流程相关参数
     * @param IFlowTaskDao $task 流程任务 [必传]
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function skipByParams(FlowParams $flowParams, IFlowTaskDao $task): ?IFlowInstanceDao
    {
        return $this->skipByFlowParams($flowParams, $task);
    }

    /**
     * 根据 instanceIds 删除
     *
     * @param array $instanceIds 流程实例 id 集合
     * @return bool
     * @throws FlowException
     */
    public function deleteByInsIds(array $instanceIds): bool
    {
        /**
         * @var IFlowInstanceDao[] $instanceList
         */
        $instanceList = FlowEngine::insService()->getByIds($instanceIds);
        foreach ($instanceList as $instance) {
            /**
             * @var IFlowDefinitionDao $definition
             */
            $definition = FlowEngine::defService()->getById($instance->getDefinitionId());
            AssertUtil::isFalse($this->judgeActivityStatus($definition, $instance), ExceptionCons::NOT_ACTIVITY);
        }
        /**
         * @var IFlowTaskDao $dao
         */
        $dao = $this->getDao();
        return SqlHelper::retBool($dao->deleteByInsIds($instanceIds));
    }

    /**
     * 拿回到最近办理的任务（根据实例 ID）
     *
     * @param int $instanceId 流程实例 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function taskBackByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        // 获取当前任务的前置任务
        $lastHisTask    = $this->taskBackByParams($flowParams, $instanceId);
        $suffixNodeList = FlowEngine::nodeService()->suffixNodeListByDefId($lastHisTask->getDefinitionId()
            , $lastHisTask->getNodeCode());

        $suffixNodeCodes = StreamUtils::toList($suffixNodeList, fn($node) => $node->getNodeCode());
        $taskList        = FlowEngine::taskService()->getByInsIdAndNodeCodes($instanceId, $suffixNodeCodes);
        AssertUtil::isEmpty($taskList, ExceptionCons::NOT_FOUNT_HANDLED_TASK_HANDLER);
        return $this->skipByFlowParams($flowParams, $taskList[0]);
    }

    /**
     * 根据流程实例id获取操作人最近的已办历史任务
     *
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @param int $instanceId 流程实例 id
     * @return IFlowHisTaskDao|null 最近的已办历史任务
     * @throws FlowException
     */
    private function taskBackByParams(FlowParams $flowParams, int $instanceId): ?IFlowHisTaskDao
    {
        $flowParams->skipType(SkipType::REJECT->value)
            ->ignore(true)
            ->ignoreDepute(true)
            ->ignoreCooperate(true)
            ->flowStatus(StringUtils::emptyDefault($flowParams->getFlowStatus(), FlowStatus::TASK_BACK->value));
        // 获取当前任务的前置任务
        $hisTaskList = FlowEngine::hisTaskService()->getByInsId($instanceId);
        // 获取 hisTaskList 中 TargetNodeCod 等于 task.getNodeCode() 的，并且 id 最大的
        $lastHisTask = null;
        foreach ($hisTaskList as $hisTask) {
            if (StringUtils::isNotEmpty($hisTask->getApprover())
                && SkipType::isPass($hisTask->getSkipType())
                && $hisTask->getApprover() === $flowParams->getHandler()) {
                if ($lastHisTask === null || $hisTask->getId() > $lastHisTask->getId()) {
                    $lastHisTask = $hisTask;
                }
            }
        }
        AssertUtil::isNull($lastHisTask, ExceptionCons::NOT_FOUNT_HANDLED_TASK);
        $flowParams->nodeCode($lastHisTask->getNodeCode());
        return $lastHisTask;
    }

    /**
     * 根据流程实例 id 和节点 code 集合获取流程任务集合
     *
     * @param int $instanceId 流程实例 id
     * @param array $nodeCodes 节点 code 集合
     * @return array<IFlowTaskDao> 任务集合
     */
    public function getByInsIdAndNodeCodes(int $instanceId, array $nodeCodes): array
    {
        /**
         * @var IFlowTaskDao $dao
         */
        $dao = $this->getDao();
        return $dao->getByInsIdAndNodeCodes($instanceId, $nodeCodes);
    }

    /**
     * 拿回到最近办理的任务（根据任务 ID）
     *
     * @param int $taskId 流程任务 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function taskBack(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        /**
         * @var IFlowTaskDao $task
         */
        $task = $this->getById($taskId);
        AssertUtil::isNull($task, ExceptionCons::NOT_FOUNT_TASK);
        $this->taskBackByParams($flowParams, $task->getInstanceId());
        return $this->skipByFlowParams($flowParams, $task);
    }

    /**
     * 撤销
     *
     * @param int $instanceId 实例 id [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     * @throws Exception
     */
    public function revoke(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        $flowParams->skipType(SkipType::REJECT->value);
        // 删除待办任务，保存历史，删除所有代办任务的权限人
        if (StringUtils::isEmpty($flowParams->getFlowStatus())) {
            $flowParams->flowStatus(FlowStatus::CANCEL->value);
        }
        /**
         * @var IFlowInstanceDao $instance
         */
        $instance = FlowEngine::insService()->getById($instanceId);
        $flowParams->variable(MapUtil::mergeAll($instance->getVariableMap(), $flowParams->getVariable()));
        AssertUtil::isNull($instance, ExceptionCons::NOT_FOUNT_INSTANCE);
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = FlowEngine::defService()->getById($instance->getDefinitionId());
        AssertUtil::isFalse($this->judgeActivityStatus($definition, $instance), ExceptionCons::NOT_ACTIVITY);
        AssertUtil::isTrue(NodeType::isEnd($instance->getNodeType()), ExceptionCons::FLOW_FINISH);
        $flowParams->variable(MapUtil::mergeAll($instance->getVariableMap(), $flowParams->getVariable()));

        $taskList    = $this->getByInsId($instanceId);
        $flowCombine = FlowEngine::defService()->getFlowCombineByDef($definition);
        $nodeMap     = StreamUtils::toMap($flowCombine->getAllNodes(), fn($node) => $node->getNodeCode(), fn($node) => $node);

        // 执行开始监听器
        foreach ($taskList as $task) {
            ListenerUtil::executeListener((new ListenerVariable($definition, $instance,
                $nodeMap[$task->getNodeCode()], $flowParams->getVariable(), $task))
                ->setFlowParams($flowParams), Listener::LISTENER_START);
        }

        // 验证权限是不是当前任务的发起人
        if (!$flowParams->isIgnore()) {
            AssertUtil::isFalse($instance->getCreateBy() === $flowParams->getHandler()
                , ExceptionCons::NOT_DEF_PROMOTER_NOT_CANCEL);
        }

        // 获取开始节点
        $startNode = StreamUtils::filterOne($flowCombine->getAllNodes(), fn($node) => NodeType::isStart($node->getNodeType()));
        // 获取下一个节点，如果是网关节点，则重新获取后续节点
        $pathWayData = new PathWayData();
        $pathWayData->setInsId($instanceId)->setSkipType($flowParams->getSkipType());
        $nextNode    = FlowEngine::nodeService()->getNextNodeByNode($startNode, null, SkipType::PASS->value
            , null, $flowCombine);
        $nextNodes   = FlowEngine::nodeService()->getNextByCheckGateway($flowParams->getVariable(), $nextNode
            , $pathWayData, $flowCombine);
        $targetNodes = $pathWayData->getTargetNodes();
        $targetNodes = array_merge($nextNodes, $targetNodes);
        $pathWayData->setTargetNodes($targetNodes);
        // 设置流程图元数据
        $instance->setDefJson(FlowEngine::chartService()->skipMetadata($pathWayData));

        // 查询任务，如果前一个节点是并行网关，可能任务表有多个任务，增加查询和判断
        $curTaskList = $this->list(FlowEngine::newTask()->setInstanceId($instance->getId()));
        AssertUtil::isEmpty($curTaskList, ExceptionCons::NOT_FOUND_FLOW_TASK);

        // 给回退到的那个节点赋权限 - 给当前处理人权限
        $addTasks = StreamUtils::toList($nextNodes, fn($node) => $this->addTask($node, $instance, $definition, $flowParams));

        // 办理人变量替换
        ExpressionUtil::evalVariable($addTasks, $flowParams->variable(MapUtil::mergeAll($instance->getVariableMap(), $flowParams->getVariable())));

        // 执行分派监听器
        foreach ($taskList as $task) {
            ListenerUtil::executeListener((new ListenerVariable($definition, $instance,
                $nodeMap[$task->getNodeCode()], $flowParams->getVariable(), $task, $nextNodes, $addTasks))
                ->setFlowParams($flowParams), Listener::LISTENER_ASSIGNMENT);
        }

        // 设置流程历史任务信息
        $insHisList = FlowEngine::hisTaskService()->setSkipHisList($curTaskList, $nextNodes, $flowParams);
        FlowEngine::hisTaskService()->saveBatch($insHisList);
        // 待办任务和处理人
        $this->removeAndUser($curTaskList);
        $users = FlowEngine::userService()->taskAddUsers($addTasks);

        // 设置任务完成后的实例相关信息
        $this->setInsFinishInfo($instance, $addTasks, $flowParams);
        if (CollUtil::isNotEmpty($addTasks)) {
            $this->saveBatch($addTasks);
        }
        FlowEngine::insService()->updateById($instance);
        // 保存下一个待办任务的权限人
        FlowEngine::userService()->saveBatch($users);

        // 执行完成和创建监听器
        foreach ($taskList as $task) {
            ListenerUtil::endCreateListener((new ListenerVariable($definition, $instance,
                $nodeMap[$task->getNodeCode()], $flowParams->getVariable(), $task, $nextNodes, $addTasks))
                ->setFlowParams($flowParams));
        }
        return $instance;
    }

    /**
     * 终止流程，提前结束流程，将所有待办任务转历史
     *
     * @param int $instanceId 流程实例 id[必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function terminationByInsId(int $instanceId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        // 获取待办任务
        $taskList = FlowEngine::taskService()->getByInsId($instanceId);
        AssertUtil::isEmpty($taskList, ExceptionCons::NOT_FOUNT_TASK);
        $task = $taskList[0];
        return $this->terminationInner($task, $flowParams);
    }

    /**
     * 终止流程，提前结束流程，将所有待办任务转历史
     *
     * @param IFlowTaskDao|null $task 流程任务
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     * @throws Exception
     */
    private function terminationInner(?IFlowTaskDao $task, FlowParams $flowParams): ?IFlowInstanceDao
    {
        $r = $this->getAndCheckByTaskObj($task);
        $flowParams->skipType(SkipType::PASS->value);
        $variableArr = MapUtil::mergeAll($r->instance->getVariableMap(), $flowParams->getVariable());
        $flowParams->variable($variableArr);
        ListenerUtil::executeListener((new ListenerVariable($r->definition, $r->instance, $r->nowNode,
            $flowParams->getVariable(), $task))->setFlowParams($flowParams), Listener::LISTENER_START);

        // 判断当前处理人是否有权限处理
        $task->setUserList(FlowEngine::userService()->listByAssociatedAndTypes($task->getId()));
        $this->checkAuth($task, $flowParams);

        // 所有待办转历史
        $endNode = FlowEngine::nodeService()->getEndNode($r->instance->getDefinitionId());

        // 设置流程图元数据
        $pathWayData = new PathWayData();
        $pathWayData->setInsId($task->getInstanceId())
            ->setSkipType($flowParams->getSkipType())
            ->setPathWayNodes([$r->nowNode])
            ->setTargetNodes([$endNode]);
        $r->instance->setDefJson(FlowEngine::chartService()->skipMetadata($pathWayData));

        // 流程实例完成
        $r->instance->setNodeType($endNode->getNodeType())
            ->setNodeCode($endNode->getNodeCode())
            ->setNodeName($endNode->getNodeName())
            ->setFlowStatus(StringUtils::emptyDefault($flowParams->getFlowStatus(), FlowStatus::TERMINATE->value));

        // 待办任务转历史
        $flowParams->flowStatus($r->instance->getFlowStatus());
        $insHis = FlowEngine::hisTaskService()->setSkipInsHis($task, [$endNode], $flowParams);
        FlowEngine::hisTaskService()->save($insHis);
        FlowEngine::insService()->updateById($r->instance);

        // 删除流程相关办理人
        FlowEngine::userService()->deleteByTaskIds([$task->getId()]);

        // 处理未完成的任务，当流程完成，还存在待办任务未完成，转历史任务，状态完成。
        $this->handUndoneTask($r->instance);
        // 最后判断是否存在节点监听器，存在执行节点监听器
        ListenerUtil::executeListener((new ListenerVariable($r->definition, $r->instance, $r->nowNode,
            $flowParams->getVariable(), $task))->setFlowParams($flowParams), Listener::LISTENER_FINISH);
        return $r->instance;
    }

    /**
     * 终止流程，提前结束流程，将所有待办任务转历史
     *
     * @param int $taskId 流程任务 id [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function termination(int $taskId, FlowParams $flowParams): ?IFlowInstanceDao
    {
        /**
         * @var IFlowTaskDao $task
         */
        $task = $this->getById($taskId);
        return $this->terminationByTask($task, $flowParams);
    }

    /**
     * 终止流程，提前结束流程，将所有待办任务转历史
     *
     * @param IFlowTaskDao|null $task 流程任务 [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return IFlowInstanceDao|null
     * @throws FlowException
     */
    public function terminationByTask(?IFlowTaskDao $task, FlowParams $flowParams): ?IFlowInstanceDao
    {
        return $this->terminationInner($task, $flowParams);
    }

    /**
     * 转办，默认删除当前办理用户权限，转办后，当前办理不可办理
     *
     * @param int $taskId 修改的任务 id [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return bool
     * @throws FlowException
     */
    public function transfer(int $taskId, FlowParams $flowParams): bool
    {
        AssertUtil::isNull($taskId, ExceptionCons::NULL_TASK_ID);
        AssertUtil::isNull($flowParams->getHandler(), ExceptionCons::HANDLER_NOT_EMPTY);
        AssertUtil::isNull($flowParams->getAddHandlers(), ExceptionCons::NULL_TRANSFER_HANDLER);
        $users = FlowEngine::userService()->getByProcessedBys($taskId, $flowParams->getAddHandlers(), UserType::TRANSFER->value);
        AssertUtil::isNotEmpty($users, ExceptionCons::IS_ALREADY_TRANSFER);
        $flowParams->cooperateType(CooperateType::TRANSFER->value)
            ->reductionHandlers([$flowParams->getHandler()]);


        return $this->updateHandler($taskId, $flowParams);
    }

    /**
     * 修改办理人
     *
     * @param int $taskId 修改的任务 id [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return bool
     * @throws FlowException
     * @throws Exception
     */
    public function updateHandler(int $taskId, FlowParams $flowParams): bool
    {
        // 获取待办任务
        $r = $this->getAndCheck($taskId);
        $flowParams->variable(MapUtil::mergeAll($r->instance->getVariableMap(), $flowParams->getVariable()));
        // 执行开始监听器
        ListenerUtil::executeListener(new ListenerVariable($r->definition, $r->instance, $r->nowNode, null,
            $r->task), Listener::LISTENER_START);

        // 获取给谁的权限
        if (!$flowParams->isIgnore()) {
            // 判断当前处理人是否有权限，获取当前办理人的权限
            $permissions = $flowParams->getPermissionFlag();
            // 获取任务权限人
            $taskPermissions = FlowEngine::userService()->getPermission($taskId
                , UserType::APPROVAL->value, UserType::TRANSFER->value, UserType::DEPUTE->value);
            AssertUtil::isTrue(CollUtil::isNotEmpty($taskPermissions) && (CollUtil::isEmpty($permissions)
                    || CollUtil::notContainsAny($permissions, $taskPermissions)), ExceptionCons::NOT_AUTHORITY);
        }
        // 留存历史记录
        $flowParams->skipType(SkipType::NONE->value);
        $hisTask = null;
        // 删除对应的操作人
        if (CollUtil::isNotEmpty($flowParams->getReductionHandlers())) {
            foreach ($flowParams->getReductionHandlers() as $reductionHandler) {
                FlowEngine::userService()->remove(FlowEngine::newUser()->setAssociated($taskId)
                    ->setProcessedBy($reductionHandler));
            }
            $hisTask = FlowEngine::hisTaskService()->setCooperateHis($r->task, $r->nowNode
                , $flowParams, $flowParams->getReductionHandlers());
        }

        // 新增权限人
        if (CollUtil::isNotEmpty($flowParams->getAddHandlers())) {
            if (CooperateType::TRANSFER->value === $flowParams->getCooperateType()) {
                $type = UserType::TRANSFER->value;
            } elseif (CooperateType::DEPUTE->value === $flowParams->getCooperateType()) {
                $type = UserType::DEPUTE->value;
            } else {
                $type = UserType::APPROVAL->value;
            }
            FlowEngine::userService()->saveBatch(StreamUtils::toList($flowParams->getAddHandlers(), fn($permission) => FlowEngine::userService()->structureSingleUserWithHandler($taskId, $permission
                , $type, $flowParams->getHandler())));
            $hisTask = FlowEngine::hisTaskService()->setCooperateHis($r->task, $r->nowNode
                , $flowParams, $flowParams->getAddHandlers());
        }
        if (ObjectUtil::isNotNull($hisTask)) {
            FlowEngine::hisTaskService()->save($hisTask);
        }
        // 最后判断是否存在节点监听器，存在执行节点监听器
        ListenerUtil::executeListener(new ListenerVariable($r->definition, $r->instance, $r->nowNode, $flowParams->getVariable(),
            $r->task), Listener::LISTENER_FINISH);
        return true;
    }

    /**
     * 获取并检查
     *
     * @param int $taskId 任务 id
     * @return TaskCheckResult
     * @throws FlowException
     */
    private function getAndCheck(int $taskId): TaskCheckResult
    {
        AssertUtil::isNull($taskId, ExceptionCons::NULL_TASK_ID);
        /**
         * @var IFlowTaskDao $task
         */
        $task = $this->getById($taskId);
        return $this->getAndCheckByTaskObj($task);
    }

    /**
     * 委派，默认删除当前办理用户权限，委派后审批完，重新回到当前办理人
     *
     * @param int $taskId 修改的任务 id [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return bool
     * @throws FlowException
     */
    public function depute(int $taskId, FlowParams $flowParams): bool
    {
        AssertUtil::isNull($taskId, ExceptionCons::NULL_TASK_ID);
        AssertUtil::isNull($flowParams->getHandler(), ExceptionCons::HANDLER_NOT_EMPTY);
        AssertUtil::isNull($flowParams->getAddHandlers(), ExceptionCons::NULL_DEPUTE_HANDLER);
        $users = FlowEngine::userService()->getByProcessedBys($taskId, $flowParams->getAddHandlers(), UserType::DEPUTE->value);
        AssertUtil::isNotEmpty($users, ExceptionCons::IS_ALREADY_DEPUTE);
        $flowParams->cooperateType(CooperateType::DEPUTE->value)
            ->reductionHandlers([$flowParams->getHandler()]);

        return $this->updateHandler($taskId, $flowParams);
    }

    /**
     * 加签，增加办理人
     *
     * @param int $taskId 修改的任务 id [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return bool
     * @throws FlowException
     */
    public function addSignature(int $taskId, FlowParams $flowParams): bool
    {
        AssertUtil::isNull($taskId, ExceptionCons::NULL_TASK_ID);
        AssertUtil::isNull($flowParams->getHandler(), ExceptionCons::HANDLER_NOT_EMPTY);
        AssertUtil::isNull($flowParams->getAddHandlers(), ExceptionCons::NULL_ADD_SIGNATURE_HANDLER);
        $users = FlowEngine::userService()->getByProcessedBys($taskId, $flowParams->getAddHandlers(), UserType::APPROVAL->value);
        AssertUtil::isNotEmpty($users, ExceptionCons::IS_ALREADY_SIGN);
        $flowParams->cooperateType(CooperateType::ADD_SIGNATURE->value);

        return $this->updateHandler($taskId, $flowParams);
    }

    /**
     * 减签，减少办理人
     *
     * @param int $taskId 修改的任务 id [必传]
     * @param FlowParams $flowParams 包含流程相关参数的对象
     * @return bool
     * @throws FlowException
     */
    public function reductionSignature(int $taskId, FlowParams $flowParams): bool
    {
        AssertUtil::isNull($taskId, ExceptionCons::NULL_TASK_ID);
        AssertUtil::isNull($flowParams->getHandler(), ExceptionCons::HANDLER_NOT_EMPTY);
        AssertUtil::isNull($flowParams->getReductionHandlers(), ExceptionCons::NULL_REDUCTION_SIGNATURE_HANDLER);
        $users = FlowEngine::userService()->listByAssociatedAndTypes($taskId
            , UserType::APPROVAL->value, UserType::TRANSFER->value);
        AssertUtil::isTrue(CollUtil::isEmpty($users) || count($users) === 1, ExceptionCons::REDUCTION_SIGN_ONE_ERROR);
        $flowParams->cooperateType(CooperateType::REDUCTION_SIGNATURE->value);

        return $this->updateHandler($taskId, $flowParams);
    }

    /**
     * 获取表单及数据 (使用表单场景)
     *
     * @param int $taskId
     * @param FlowParams $flowParams
     * @return FlowDto|null
     * @throws FlowException
     * @throws Exception
     */
    public function load(int $taskId, FlowParams $flowParams): ?FlowDto
    {
        $r = $this->getAndCheck($taskId);

        $listenerVariable = new ListenerVariable($r->definition, $r->instance, $r->nowNode
            , $flowParams->getVariable(), $r->task);

        $flowDto = new FlowDto();
        if (FlowCons::FORM_CUSTOM_Y === $r->nowNode->getFormCustom()) {
            ListenerUtil::execute($listenerVariable, Listener::LISTENER_FORM_LOAD, $r->nowNode->getListenerPath()
                , $r->nowNode->getListenerType());
            /**
             * @var IFlowFormDao $form
             */
            $form = FlowEngine::formService()->getById((int)$r->task->getFormPath());
            $flowDto->setForm($form);
        } elseif (StringUtils::isEmpty($r->nowNode->getFormCustom()) && FlowCons::FORM_CUSTOM_Y === $r->definition->getFormCustom()) {
            ListenerUtil::execute($listenerVariable, Listener::LISTENER_FORM_LOAD, $r->definition->getListenerPath()
                , $r->definition->getListenerType());
            /**
             * @var IFlowFormDao $form
             */
            $form = FlowEngine::formService()->getById((int)$r->definition->getFormPath());
            $flowDto->setForm($form);
        }
        $flowDto->setData($r->instance->getVariable()[FlowCons::FORM_DATA] ?? null);

        return $flowDto;
    }

    /**
     * 获取表单及数据 (使用表单场景)
     *
     * @param int $hisTaskId
     * @param FlowParams $flowParams
     * @return FlowDto|null
     * @throws FlowException
     */
    public function hisLoad(int $hisTaskId, FlowParams $flowParams): ?FlowDto
    {
        /**
         * @var IFlowHisTaskDao $hisTask
         */
        $hisTask = FlowEngine::hisTaskService()->getById($hisTaskId);
        AssertUtil::isNull($hisTask, ExceptionCons::NOT_FOUND_FLOW_TASK);
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = FlowEngine::defService()->getById($hisTask->getDefinitionId());
        AssertUtil::isNull($definition, ExceptionCons::NOT_FOUNT_DEF);

        $nowNode = CollUtil::getOne(FlowEngine::nodeService()
            ->getByNodeCodes([$hisTask->getNodeCode()], $hisTask->getDefinitionId()));
        AssertUtil::isNull($nowNode, ExceptionCons::LOST_CUR_NODE);

        $flowDto = new FlowDto();
        if (FlowCons::FORM_CUSTOM_Y === $nowNode->getFormCustom()) {
            /**
             * @var IFlowFormDao $form
             */
            $form = FlowEngine::formService()->getById((int)$hisTask->getFormPath());
            $flowDto->setForm($form);
        } elseif (StringUtils::isEmpty($nowNode->getFormCustom()) && FlowCons::FORM_CUSTOM_Y === $definition->getFormCustom()) {
            /**
             * @var IFlowFormDao $form
             */
            $form = FlowEngine::formService()->getById((int)$definition->getFormPath());
            $flowDto->setForm($form);
        }
        $flowDto->setData($hisTask->getVariable()[FlowCons::FORM_DATA] ?? null);

        return $flowDto;
    }
}
