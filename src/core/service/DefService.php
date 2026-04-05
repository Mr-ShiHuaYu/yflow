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

use Exception;
use Yflow\core\dto\DefJson;
use Yflow\core\dto\FlowCombine;
use Yflow\core\orm\dao\IFlowDefinitionDao;
use Yflow\core\orm\service\IWarmService;


/**
 * DefService - 流程定义 Service 接口
 *
 *
 * @since 2023-03-29
 */
interface DefService extends IWarmService
{

    /**
     * 导入流程定义、流程节点和流程跳转数据
     *
     * @param mixed $is 流程定义的输入流
     * @return IFlowDefinitionDao|null
     */
    public function importIs(mixed $is): ?IFlowDefinitionDao;

    /**
     * 导入流程定义、流程节点和流程跳转数据
     *
     * @param string $defJson
     * @return IFlowDefinitionDao|null
     */
    public function importJson(string $defJson): ?IFlowDefinitionDao;

    /**
     * 导入流程定义、流程节点和流程跳转数据
     *
     * @param DefJson $defJson 流程定义 json 对象，流程定义、流程节点和流程跳转按照主子集传递
     * @return IFlowDefinitionDao|null
     */
    public function importDef(DefJson $defJson): ?IFlowDefinitionDao;

    /**
     * 新增工作流定义，并初始化流程节点和流程跳转数据
     *
     * @param IFlowDefinitionDao $definition 流程定义
     * @param array $nodeList 流程节点
     * @param array $skipList 流程跳转
     * @return IFlowDefinitionDao|null
     */
    public function insertFlow(IFlowDefinitionDao $definition, array $nodeList, array $skipList): ?IFlowDefinitionDao;

    /**
     * 只新增流程定义表数据
     *
     * @param IFlowDefinitionDao $definition 流程定义对象
     * @return bool
     */
    public function checkAndSave(IFlowDefinitionDao $definition): bool;

    /**
     * 保存流程节点和跳转
     *
     * @param DefJson $defJson 流程定义 json 对象
     * @param bool $onlyNodeSkip 是否只保存节点和跳转
     * @return void
     * @throws Exception
     */
    public function saveDef(DefJson $defJson, bool $onlyNodeSkip): void;

    /**
     * 导出流程定义 (流程定义、流程节点和流程跳转数据) 的 json 字符串
     *
     * @param int $id 流程定义 id
     * @return string json 字符串
     */
    public function exportJson(int $id): string;

    /**
     * 获取流程定义全部数据 (包含节点和跳转)
     *
     * @param int $id 流程定义 id
     * @return IFlowDefinitionDao|null
     */
    public function getAllDataDefinition(int $id): ?IFlowDefinitionDao;

    /**
     * 流程数据集合
     *
     * @param int $id 流程定义 id
     * @return FlowCombine
     */
    public function getFlowCombine(int $id): FlowCombine;

    /**
     * 流程数据集合不包含流程定义
     *
     * @param int $id 流程定义 id
     * @return FlowCombine
     */
    public function getFlowCombineNoDef(int $id): FlowCombine;

    /**
     * 流程数据集合
     *
     * @param IFlowDefinitionDao $definition 流程定义
     * @return FlowCombine
     */
    public function getFlowCombineByDef(IFlowDefinitionDao $definition): FlowCombine;

    /**
     * 查询流程设计所需的数据，比如流程图渲染
     *
     * @param int $id 流程定义 id
     * @return DefJson 流程定义 json 对象
     */
    public function queryDesign(int $id): DefJson;

    /**
     * 根据流程定义 code 列表查询流程定义
     *
     * @param array $flowCodeList 流程定义 code 列表
     * @return array<IFlowDefinitionDao> 流程定义列表
     */
    public function queryByCodeList(array $flowCodeList): array;

    /**
     * 更新流程定义发布状态
     *
     * @param array $defIds 流程定义 id 列表
     * @param int $publishStatus 流程定义发布状态
     * @return void
     */
    public function updatePublishStatus(array $defIds, int $publishStatus): void;

    /**
     * 删除流程定义相关数据
     *
     * @param array $ids 流程定义 id 列表
     * @return bool
     */
    public function removeDef(array $ids): bool;

    /**
     * 发布流程定义
     *
     * @param int $id 流程定义 id
     * @return bool
     */
    public function publish(int $id): bool;

    /**
     * 取消发布流程定义
     *
     * @param int $id 流程定义 id
     * @return bool
     */
    public function unPublish(int $id): bool;

    /**
     * 复制流程定义
     *
     * @param int $id 流程定义 id
     * @return bool
     */
    public function copyDef(int $id): bool;

    /**
     * 激活流程
     *
     * @param int $id 流程定义 id
     * @return bool
     */
    public function active(int $id): bool;

    /**
     * 挂起流程：流程定义挂起后，相关的流程实例都无法继续流转
     *
     * @param int $id 流程定义 id
     * @return bool
     */
    public function unActive(int $id): bool;

    /**
     * 根据流程定义 code 查询流程定义
     *
     * @param string $flowCode 流程定义 code
     * @return array<IFlowDefinitionDao>
     */
    public function getByFlowCode(string $flowCode): array;

    /**
     * 根据流程定义 code 查询已发布的流程定义
     *
     * @param string $flowCode 流程定义 code
     * @return IFlowDefinitionDao|null
     */
    public function getPublishByFlowCode(string $flowCode): ?IFlowDefinitionDao;
}
