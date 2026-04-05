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
use Yflow\core\dto\DefJson;
use Yflow\core\dto\FlowCombine;
use Yflow\core\enums\ActivityStatus;
use Yflow\core\enums\PublishStatus;
use Yflow\core\exception\FlowException;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\orm\dao\IFlowDefinitionDao;
use Yflow\core\orm\service\impl\WarmServiceImpl;
use Yflow\core\service\DefService;
use Yflow\core\utils\AssertUtil;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\FlowConfigUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\StreamUtils;
use Yflow\core\utils\StringUtils;


/**
 * DefServiceImpl - 流程定义 Service 业务层处理
 * @extends WarmServiceImpl<IFlowDefinitionDao>
 */
class DefServiceImpl extends WarmServiceImpl implements DefService
{

    public function __construct()
    {
        $this->setDao(FrameInvoker::getBean(IFlowDefinitionDao::class));
    }

    public function getDao(): IFlowDefinitionDao
    {
        return $this->warmDao;
    }

    /**
     * 设置 DAO
     *
     * @param IFlowDefinitionDao $warmDao DAO
     * @return DefService
     */
    public function setDao($warmDao): DefService
    {
        $this->warmDao = $warmDao;
        return $this;
    }

    /**
     * 导入流程定义、流程节点和流程跳转数据
     *
     * @param mixed $is 流程定义的输入流
     * @return IFlowDefinitionDao|null
     * @throws FlowException
     */
    public function importIs(mixed $is): ?IFlowDefinitionDao
    {
        $contents = file_get_contents($is);
        if (empty($contents)) {
            throw new FlowException(ExceptionCons::READ_IS_ERROR);
        }

        return $this->importJson($contents);
    }

    /**
     * 导入流程定义、流程节点和流程跳转数据
     *
     * @param string $defJson 流程定义的 json 字符串
     * @return IFlowDefinitionDao|null
     * @throws FlowException
     */
    public function importJson(string $defJson): ?IFlowDefinitionDao
    {
        $obj = FlowEngine::$jsonConvert->strToBean($defJson, DefJson::class);
        if ($obj instanceof DefJson) {
            return $this->importDef($obj);
        }
        return null;
    }

    /**
     * 导入流程定义、流程节点和流程跳转数据
     *
     * @param DefJson $defJson 流程定义 json 对象
     * @return IFlowDefinitionDao|null
     * @throws FlowException
     */
    public function importDef(DefJson $defJson): ?IFlowDefinitionDao
    {
        $definition  = DefJson::copyDefDto2Model($defJson);
        $flowCombine = FlowConfigUtil::structureFlow($definition);
        return $this->insertFlow($flowCombine->getDefinition(), $flowCombine->getAllNodes(), $flowCombine->getAllSkips());
    }

    /**
     * 新增工作流定义，并初始化流程节点和流程跳转数据
     *
     * @param IFlowDefinitionDao $definition 流程定义
     * @param array $nodeList 流程节点
     * @param array $skipList 流程跳转
     * @return IFlowDefinitionDao|null
     */
    public function insertFlow(IFlowDefinitionDao $definition, array $nodeList, array $skipList): ?IFlowDefinitionDao
    {
        $definition->setVersion($this->getNewVersion($definition));
        foreach ($nodeList as $node) {
            $node->setVersion($definition->getVersion());
        }
        FlowEngine::defService()->save($definition);

        // 确保 $nodeList 中的元素都是实体对象
        $nodeArray = [];
        foreach ($nodeList as $node) {
            if (is_object($node)) {
                $nodeArray[] = $node;
            }
        }
        FlowEngine::nodeService()->saveBatch($nodeArray);

        // 确保 $skipList 中的元素都是实体对象
        $skipArray = [];
        foreach ($skipList as $skip) {
            if (is_object($skip)) {
                $skipArray[] = $skip;
            }
        }
        FlowEngine::skipService()->saveBatch($skipArray);
        return $definition;
    }

    /**
     * 只新增流程定义表数据
     *
     * @param IFlowDefinitionDao $definition 流程定义对象
     * @return bool
     */
    public function checkAndSave(IFlowDefinitionDao $definition): bool
    {
        return $this->save($definition->setVersion($this->getNewVersion($definition)));
    }

    /**
     * 保存流程节点和跳转
     *
     * @param DefJson $defJson 流程定义 json 对象
     * @param bool $onlyNodeSkip 是否只保存节点和跳转
     * @return void
     * @throws Exception
     */
    public function saveDef(DefJson $defJson, bool $onlyNodeSkip): void
    {
        if (ObjectUtil::isNull($defJson)) {
            return;
        }

        $flowCombine = DefJson::copyCombine($defJson);
        $definition  = $flowCombine->getDefinition();
        $id          = $definition->getId();

        // 如果是新增的流程定义
        if (ObjectUtil::isNull($id)) {
            $definition->setVersion($this->getNewVersion($definition));
            FlowEngine::dataFillHandler()->idFill($definition);
        }

        // 校验流程定义合法性
        $this->checkFlowLegal($flowCombine);

        // 如果是新增的流程定义
        if (ObjectUtil::isNull($id)) {
            FlowEngine::defService()->save($definition);
        } else {
            if (!$onlyNodeSkip) {
                FlowEngine::defService()->updateById($definition);
            }
            // 删除所有节点和连线
            FlowEngine::nodeService()->remove(FlowEngine::newNode()->setDefinitionId($id));
            FlowEngine::skipService()->remove(FlowEngine::newSkip()->setDefinitionId($id));
        }

        // 保存流程节点和跳转
        $allNodes = $flowCombine->getAllNodes();
        foreach ($allNodes as $node) {
            if (StringUtils::isEmpty($node->getNodeRatio())) {
                $node->setNodeRatio(StringUtils::ZERO);
            }
        }

        $allSkips = $flowCombine->getAllSkips();

        // 保存节点，流程连线，权利人
        FlowEngine::nodeService()->saveBatch($allNodes);
        FlowEngine::skipService()->saveBatch($allSkips);
    }

    /**
     * 导出流程定义 (流程定义、流程节点和流程跳转数据) 的 json 字符串
     *
     * @param int $id 流程定义 id
     * @return string json 字符串
     */
    public function exportJson(int $id): string
    {
        return FlowEngine::$jsonConvert->objToStr($this->queryDesign($id)->setIsPublish(null));
    }

    /**
     * 获取流程定义全部数据 (包含节点和跳转)
     *
     * @param int $id 流程定义 id
     * @return IFlowDefinitionDao|null
     * @throws FlowException
     */
    public function getAllDataDefinition(int $id): ?IFlowDefinitionDao
    {
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = $this->getDao()->selectById($id);
        AssertUtil::isNull($definition, ExceptionCons::NOT_FOUNT_DEF);
        $nodeList = FlowEngine::nodeService()->getByDefId($id);
        $definition->setNodeList($nodeList);
        $skips = FlowEngine::skipService()->getByDefId($id);

        // 按当前节点 NowNodeCode 分组
        $flowSkipMap = [];
        foreach ($skips as $skip) {
            $flowSkipMap[$skip->getNowNodeCode()][] = $skip;
        }

        foreach ($nodeList as $flowNode) {
            $flowNode->setSkipList($flowSkipMap[$flowNode->getNodeCode()] ?? []);
        }

        return $definition;
    }

    /**
     * 流程数据集合
     *
     * @param int $id 流程定义 id
     * @return FlowCombine
     */
    public function getFlowCombine(int $id): FlowCombine
    {
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = $this->getDao()->selectById($id);
        return $this->getFlowCombineByDef($definition);
    }

    /**
     * 流程数据集合不包含流程定义
     *
     * @param int $id 流程定义 id
     * @return FlowCombine
     */
    public function getFlowCombineNoDef(int $id): FlowCombine
    {
        $flowCombine = new FlowCombine();
        $flowCombine->setAllNodes(FlowEngine::nodeService()->getByDefId($id));
        $flowCombine->setAllSkips(FlowEngine::skipService()->getByDefId($id));
        return $flowCombine;
    }

    /**
     * 流程数据集合
     *
     * @param IFlowDefinitionDao $definition 流程定义
     * @return FlowCombine
     */
    public function getFlowCombineByDef(IFlowDefinitionDao $definition): FlowCombine
    {
        $flowCombine = $this->getFlowCombineNoDef($definition->getId());
        $flowCombine->setDefinition($definition);
        return $flowCombine;
    }

    /**
     * 查询流程设计所需的数据，比如流程图渲染
     *
     * @param int $id 流程定义 id
     * @return DefJson 流程定义 json 对象
     */
    public function queryDesign(int $id): DefJson
    {
        return DefJson::copyDefModel2Dto($this->getAllDataDefinition($id));
    }

    /**
     * 根据流程定义 code 列表查询流程定义
     *
     * @param array $flowCodeList 流程定义 code 列表
     * @return array<IFlowDefinitionDao> 流程定义列表
     */
    public function queryByCodeList(array $flowCodeList): array
    {
        /**
         * @var IFlowDefinitionDao $dao
         */
        $dao = $this->getDao();
        return $dao->queryByCodeList($flowCodeList);
    }

    /**
     * 更新流程定义发布状态
     *
     * @param array $defIds
     * @param int $publishStatus 流程定义发布状态
     * @return void
     */
    public function updatePublishStatus(array $defIds, int $publishStatus): void
    {
        /**
         * @var IFlowDefinitionDao $dao
         */
        $dao = $this->getDao();
        $dao->updatePublishStatus($defIds, $publishStatus);
    }

    /**
     * 删除流程定义
     *
     * @param array $ids 流程定义 id
     * @return bool
     * @throws FlowException
     */
    public function removeDef(array $ids): bool
    {
        foreach ($ids as $id) {
            $instances = FlowEngine::insService()->getByDefId($id);
            AssertUtil::isNotEmpty($instances, ExceptionCons::EXIST_START_TASK);
        }
        FlowEngine::nodeService()->deleteNodeByDefIds($ids);
        FlowEngine::skipService()->deleteSkipByDefIds($ids);
        return $this->removeByIds($ids);
    }

    /**
     * 发布流程定义
     *
     * @param int $id 流程定义 id
     * @return bool
     * @throws FlowException
     */
    public function publish(int $id): bool
    {
        $nodeList = FlowEngine::nodeService()->getByDefId($id);
        AssertUtil::isEmpty($nodeList, ExceptionCons::NOT_DRAW_FLOW_ERROR);
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition  = $this->getById($id);
        $definitions = $this->getByFlowCode($definition->getFlowCode());

        // 已发布流程定义，改为已失效或者未发布状态
        $otherDefIds = [];
        foreach ($definitions as $item) {
            if (strcmp($definition->getId(), $item->getId()) !== 0
                && $item->getIsPublish() === PublishStatus::PUBLISHED) {
                $otherDefIds[] = $item->getId();
            }
        }

        if (CollUtil::isNotEmpty($otherDefIds)) {
            $instanceList = FlowEngine::insService()->listByDefIds($otherDefIds);
            if (CollUtil::isNotEmpty($instanceList)) {
                // 已发布已使用过的流程定义
                $useDefIds = [];
                foreach ($instanceList as $instance) {
                    $useDefIds[$instance->getDefinitionId()] = true;
                }

                if (!empty($useDefIds)) {
                    // 已发布已使用过的流程定义，改为已失效
                    $this->updatePublishStatus(array_keys($useDefIds), PublishStatus::EXPIRED->value);
                    // 过滤掉已发布已使用-->已发布未使用
                    $otherDefIds = array_filter($otherDefIds, fn($id) => !isset($useDefIds[$id]));
                }
            }

            if (CollUtil::isNotEmpty($otherDefIds)) {
                // 已发布未使用过的流程定义，改为未发布
                $this->updatePublishStatus($otherDefIds, PublishStatus::UNPUBLISHED->value);
            }
        }

        $flowDefinition = FlowEngine::newDef();
        $flowDefinition->setId($id);
        $flowDefinition->setIsPublish(PublishStatus::PUBLISHED->value);
        return $this->updateById($flowDefinition);
    }

    /**
     * 取消发布流程定义
     *
     * @param int $id 流程定义 id
     * @return bool
     * @throws FlowException
     */
    public function unPublish(int $id): bool
    {
        $instances = FlowEngine::insService()->getByDefId($id);
        AssertUtil::isNotEmpty($instances, ExceptionCons::EXIST_START_TASK);
        $definition = FlowEngine::newDef()->setId($id);
        $definition->setIsPublish(PublishStatus::UNPUBLISHED->value);
        return $this->updateById($definition);
    }

    /**
     * 复制流程定义
     *
     * @param int $id 流程定义 id
     * @return bool
     * @throws FlowException
     */
    public function copyDef(int $id): bool
    {
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = clone $this->getById($id);
        $definition->setId(null); // 复制后,id不能重复,要设置为Null
        $definition->setVersion($this->getNewVersion($definition));
        AssertUtil::isNull($definition, ExceptionCons::NOT_FOUNT_DEF);

        $nodeList = [];
        foreach (FlowEngine::nodeService()->getByDefId($id) as $node) {
            $nodeList[] = $node->copy();
        }

        $skipList = [];
        foreach (FlowEngine::skipService()->getByDefId($id) as $skip) {
            $skipList[] = clone $skip;
        }

        FlowEngine::dataFillHandler()->idFill($definition);

        foreach ($nodeList as $node) {
            $node->setDefinitionId($definition->getId())->setVersion($definition->getVersion());
        }
        FlowEngine::nodeService()->saveBatch($nodeList);

        foreach ($skipList as $skip) {
            $skip->setDefinitionId($definition->getId());
        }
        FlowEngine::skipService()->saveBatch($skipList);

        return $this->save($definition);
    }

    /**
     * 激活流程
     *
     * @param int $id 流程定义 id
     * @return bool
     * @throws FlowException
     */
    public function active(int $id): bool
    {
        /***
         * @var IFlowDefinitionDao $definition
         */
        $definition = $this->getById($id);
        AssertUtil::isNull($definition, ExceptionCons::NOT_FOUNT_DEF);
        AssertUtil::isTrue(ActivityStatus::isActivity($definition->getActivityStatus()), ExceptionCons::DEFINITION_ALREADY_ACTIVITY);
        $definition->setActivityStatus(ActivityStatus::ACTIVITY->value);
        return $this->updateById($definition);
    }

    /**
     * 挂起流程：流程定义挂起后，相关的流程实例都无法继续流转
     *
     * @param int $id 流程定义 id
     * @return bool
     * @throws FlowException
     */
    public function unActive(int $id): bool
    {
        /**
         * @var IFlowDefinitionDao $definition
         */
        $definition = $this->getById($id);
        AssertUtil::isNull($definition, ExceptionCons::NOT_FOUNT_DEF);
        AssertUtil::isTrue(ActivityStatus::isSuspended($definition->getActivityStatus()), ExceptionCons::DEFINITION_ALREADY_SUSPENDED);
        $definition->setActivityStatus(ActivityStatus::SUSPENDED->value);
        return $this->updateById($definition);
    }

    /**
     * 根据流程定义 code 查询流程定义
     *
     * @param string $flowCode 流程定义 code
     * @return array<IFlowDefinitionDao>
     */
    public function getByFlowCode(string $flowCode): array
    {
        return $this->list(FlowEngine::newDef()->setFlowCode($flowCode));
    }

    /**
     * 根据流程定义 code 查询已发布的流程定义
     *
     * @param string $flowCode 流程定义 code
     * @return IFlowDefinitionDao|null
     */
    public function getPublishByFlowCode(string $flowCode): ?IFlowDefinitionDao
    {
        $entity = FlowEngine::newDef()
            ->setFlowCode($flowCode)->setIsPublish(PublishStatus::PUBLISHED->value);
        /**
         * @var IFlowDefinitionDao $one
         */
        $one = FlowEngine::defService()->getOne($entity);
        return $one;
    }

    /**
     * 获取新版本号
     *
     * @param IFlowDefinitionDao $definition 流程定义
     * @return string 版本号
     */
    private function getNewVersion(IFlowDefinitionDao $definition): string
    {
        $flowCodeList             = [$definition->getFlowCode()];
        $definitions              = $this->queryByCodeList($flowCodeList);
        $highestVersion           = 0;
        $latestNonPositiveVersion = null;
        $latestTimestamp          = PHP_INT_MIN;

        foreach ($definitions as $otherDef) {
            if ($definition->getFlowCode() === $otherDef->getFlowCode()) {
                try {
                    $version = intval($otherDef->getVersion());
                    if ($version > $highestVersion) {
                        $highestVersion = $version;
                    }
                } catch (Exception) {
                    $timestamp = strtotime($otherDef->getCreateTime());
                    if ($timestamp > $latestTimestamp) {
                        $latestTimestamp          = $timestamp;
                        $latestNonPositiveVersion = $otherDef->getVersion();
                    }
                }
            }
        }

        $version = "1";
        if ($highestVersion > 0) {
            $version = strval($highestVersion + 1);
        } elseif ($latestNonPositiveVersion !== null) {
            $version = $latestNonPositiveVersion . "_1";
        }

        return $version;
    }

    /**
     * 校验流程合法性
     *
     * @param FlowCombine $flowCombine 流程数据集合
     * @return void
     * @throws FlowException
     */
    private function checkFlowLegal(FlowCombine $flowCombine): void
    {
        $definition = $flowCombine->getDefinition();
        $flowName   = $definition->getFlowName();
        AssertUtil::isEmpty($definition->getFlowCode(), "【" . $flowName . "】流程 flowCode 为空!");

        // 节点校验
        $allNodes = $flowCombine->getAllNodes();
        $allSkips = $flowCombine->getAllSkips();

        $skipMap = StreamUtils::groupByKey($allSkips, fn($skip) => $skip->getNowNodeCode());
        foreach ($allNodes as $node) {
            $node->setSkipList($skipMap[$node->getNodeCode()] ?? []);
            unset($skipMap[$node->getNodeCode()]);
        }

        AssertUtil::isNotEmpty($skipMap, "[" . $flowName . "]" . ExceptionCons::FLOW_HAVE_USELESS_SKIP);

        // 每一个流程的开始节点个数
        $nodeCodeSet = [];
        // 便利一个流程中的各个节点
        $startNum = 0;
        foreach ($allNodes as $node) {
            FlowConfigUtil::initNodeAndCondition($node, $definition->getId(), $definition->getVersion());
            $startNum = FlowConfigUtil::checkStartAndSame($node, $startNum, $flowName, $nodeCodeSet);
        }

        AssertUtil::isTrue($startNum === 0, "[" . $flowName . "]" . ExceptionCons::LOST_START_NODE);
        // 校验跳转节点的合法性
        FlowConfigUtil::checkSkipNode($allSkips);
        // 校验所有目标节点是否都存在
        FlowConfigUtil::validaIsExistDestNode($allSkips, $nodeCodeSet);
    }
}
