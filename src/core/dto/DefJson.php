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

namespace Yflow\core\dto;

use Yflow\core\FlowEngine;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\StringUtils;
use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowInstanceModel;


/**
 * 流程定义 json 对象
 *
 *
 * @since 2023-03-29
 */
class DefJson
{
    /**
     * 主键
     */
    public ?int $id = null;

    /**
     * 流程编码
     */
    public ?string $flowCode = null;

    /**
     * 流程名称
     */
    public ?string $flowName = null;

    /**
     * 设计器模型（CLASSICS 经典模型 MIMIC 仿钉钉模型）
     */
    public ?string $modelValue = null;

    /**
     * 流程类别
     */
    public ?string $category = null;

    /**
     * 流程版本
     */
    public ?string $version = null;

    /**
     * 是否发布（0 未开启 1 开启）
     */
    public ?int $isPublish = null;

    /**
     * 审批表单是否自定义（Y=是 N=否）
     */
    public ?string $formCustom = null;

    /**
     * 审批表单路径
     */
    public ?string $formPath = null;

    /**
     * 监听器类型
     */
    public ?string $listenerType = null;

    /**
     * 监听器路径
     */
    public ?string $listenerPath = null;

    /**
     * 实例对象
     */
    public ?FlowInstanceModel $instance = null;

    /**
     * 扩展字段，预留给业务系统使用
     */
    public ?string $ext = null;

    /**
     * 扩展 map，保存业务自定义扩展属性
     */
    public array $extMap = [];

    /**
     * @var NodeJson[]
     */
    public array $nodeList = [];

    /**
     * @return array
     */
    public function getNodeList(): array
    {
        return $this->nodeList;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFlowCode(): ?string
    {
        return $this->flowCode;
    }

    /**
     * @return string|null
     */
    public function getFlowName(): ?string
    {
        return $this->flowName;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @return int|null
     */
    public function getIsPublish(): ?int
    {
        return $this->isPublish;
    }

    /**
     * @return string|null
     */
    public function getFormCustom(): ?string
    {
        return $this->formCustom;
    }

    /**
     * @return string|null
     */
    public function getFormPath(): ?string
    {
        return $this->formPath;
    }

    /**
     * @return string|null
     */
    public function getListenerType(): ?string
    {
        return $this->listenerType;
    }

    /**
     * @return string|null
     */
    public function getListenerPath(): ?string
    {
        return $this->listenerPath;
    }

    /**
     * @return FlowInstanceModel|null
     */
    public function getInstance(): ?FlowInstanceModel
    {
        return $this->instance;
    }

    /**
     * @return string|null
     */
    public function getExt(): ?string
    {
        return $this->ext;
    }

    /**
     * @return array
     */
    public function getExtMap(): array
    {
        return $this->extMap;
    }

    /**
     * @return array
     */
    public function getChartStatusColor(): array
    {
        return $this->chartStatusColor;
    }

    /**
     * @return string|null
     */
    public function getTopText(): ?string
    {
        return $this->topText;
    }

    /**
     * @return bool
     */
    public function isTopTextShow(): bool
    {
        return $this->topTextShow;
    }

    /**
     * @return string|null
     */
    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    /**
     * @return string|null
     */
    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    /**
     * @return array
     */
    public function getCategoryList(): array
    {
        return $this->categoryList;
    }

    /**
     * @return array
     */
    public function getFormPathList(): array
    {
        return $this->formPathList;
    }

    /**
     * 流程状态对应的三原色
     */
    public array $chartStatusColor = [];

    /**
     * 顶部信息：比如流程名称等
     */
    public ?string $topText = null;

    /**
     * 顶部信息：流程名称是否显示
     */
    public bool $topTextShow = true;

    /**
     * 创建人
     */
    public ?string $createBy = null;

    /**
     * 更新人
     */
    public ?string $updateBy = null;

    /**
     * 流程类别列表
     */
    public array $categoryList = [];

    /**
     * 自定义表单的唯一标识：如 formCode+version
     */
    public array $formPathList = [];

    /**
     * 获取 modelValue，如果为空则默认为 CLASSICS
     */
    public function getModelValue(): string
    {
        if (StringUtils::isEmpty($this->modelValue)) {
            $this->modelValue = 'CLASSICS';
        }
        return $this->modelValue;
    }

    /**
     * 从 FlowDefinitionModel 转为 DefJson
     */
    public static function copyDefModel2Dto(FlowDefinitionModel $model): DefJson
    {
        $defJson = (new DefJson())
            ->setFlowCode($model->getFlowCode())
            ->setFlowName($model->getFlowName())
            ->setModelValue($model->getModelValue())
            ->setVersion($model->getVersion())
            ->setIsPublish($model->getIsPublish())
            ->setCategory($model->getCategory())
            ->setFormCustom($model->getFormCustom())
            ->setFormPath($model->getFormPath())
            ->setListenerType($model->getListenerType())
            ->setListenerPath($model->getListenerPath())
            ->setExt($model->getExt())
            ->setCreateBy($model->getCreateBy())
            ->setUpdateBy($model->getUpdateBy());

        $nodeList = [];
        foreach ($model->getNodeList() as $node) {
            $nodeJson = (new NodeJson())
                ->setNodeType($node->getNodeType())
                ->setNodeCode($node->getNodeCode())
                ->setNodeName($node->getNodeName())
                ->setPermissionFlag($node->getPermissionFlag())
                ->setNodeRatio($node->getNodeRatio())
                ->setCoordinate($node->getCoordinate())
                ->setAnyNodeSkip($node->getAnyNodeSkip())
                ->setListenerType($node->getListenerType())
                ->setListenerPath($node->getListenerPath())
                ->setFormCustom($node->getFormCustom())
                ->setFormPath($node->getFormPath())
                ->setExt($node->getExt())
                ->setCreateBy($node->getCreateBy())
                ->setUpdateBy($node->getUpdateBy());

            $skipList           = [];
            $skipListCollection = $node->getSkipList();
            if (CollUtil::isNotEmpty($skipListCollection)) {
                foreach ($node->getSkipList() as $skip) {
                    $skipList[] = (new SkipJson())
                        ->setCoordinate($skip->getCoordinate())
                        ->setSkipType($skip->getSkipType())
                        ->setSkipName($skip->getSkipName())
                        ->setSkipCondition($skip->getSkipCondition())
                        ->setNowNodeCode($skip->getNowNodeCode())
                        ->setNextNodeCode($skip->getNextNodeCode())
                        ->setCreateBy($skip->getCreateBy())
                        ->setUpdateBy($skip->getUpdateBy());
                }
            }
            $nodeJson->setSkipList($skipList);
            $nodeList[] = $nodeJson;
        }
        $defJson->setNodeList($nodeList);

        return $defJson;
    }

    /**
     * 从 DefJson 复制创建 FlowDefinitionModel
     */
    public static function copyDefDto2Model(DefJson $defJson): FlowDefinitionModel
    {
        $definitionModel = FlowEngine::newDef()
            ->setId($defJson->getId())
            ->setFlowCode($defJson->getFlowCode())
            ->setFlowName($defJson->getFlowName())
            ->setModelValue($defJson->getModelValue())
            ->setVersion($defJson->getVersion())
            ->setCategory($defJson->getCategory())
            ->setFormCustom($defJson->getFormCustom())
            ->setFormPath($defJson->getFormPath())
            ->setListenerType($defJson->getListenerType())
            ->setListenerPath($defJson->getListenerPath())
            ->setExt($defJson->getExt())
            ->setCreateBy($defJson->getCreateBy())
            ->setUpdateBy($defJson->getUpdateBy());

        $nodeList = [];
        foreach ($defJson->getNodeList() as $node) {
            $nodeModel = FlowEngine::newNode()
                ->setNodeType($node->getNodeType())
                ->setNodeCode($node->getNodeCode())
                ->setNodeName($node->getNodeName())
                ->setPermissionFlag($node->getPermissionFlag())
                ->setNodeRatio($node->getNodeRatio())
                ->setCoordinate($node->getCoordinate())
                ->setAnyNodeSkip($node->getAnyNodeSkip())
                ->setListenerType($node->getListenerType())
                ->setListenerPath($node->getListenerPath())
                ->setFormCustom($node->getFormCustom())
                ->setFormPath($node->getFormPath())
                ->setExt($node->getExt())
                ->setCreateBy($node->getCreateBy())
                ->setUpdateBy($node->getUpdateBy());

            $skipList           = [];
            $skipListCollection = $node->getSkipList();
            if (CollUtil::isNotEmpty($skipListCollection)) {
                foreach ($node->getSkipList() as $skip) {
                    $skipList[] = (FlowEngine::newSkip())
                        ->setCoordinate($skip->getCoordinate())
                        ->setSkipType($skip->getSkipType())
                        ->setSkipName($skip->getSkipName())
                        ->setSkipCondition($skip->getSkipCondition())
                        ->setNowNodeCode($skip->getNowNodeCode())
                        ->setNextNodeCode($skip->getNextNodeCode())
                        ->setCreateBy($skip->getCreateBy())
                        ->setUpdateBy($skip->getUpdateBy());
                }
            }
            $nodeModel->setSkipList($skipList);
            $nodeList[] = $nodeModel;
        }
        $definitionModel->setNodeList($nodeList);

        return $definitionModel;
    }

    /**
     * Setter 链式调用
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setFlowCode(?string $flowCode): self
    {
        $this->flowCode = $flowCode;
        return $this;
    }

    public function setFlowName(?string $flowName): self
    {
        $this->flowName = $flowName;
        return $this;
    }

    public function setModelValue(?string $modelValue): self
    {
        $this->modelValue = $modelValue;
        return $this;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function setIsPublish(?int $isPublish): self
    {
        $this->isPublish = $isPublish;
        return $this;
    }

    public function setFormCustom(?string $formCustom): self
    {
        $this->formCustom = $formCustom;
        return $this;
    }

    public function setFormPath(?string $formPath): self
    {
        $this->formPath = $formPath;
        return $this;
    }

    public function setListenerType(?string $listenerType): self
    {
        $this->listenerType = $listenerType;
        return $this;
    }

    public function setListenerPath(?string $listenerPath): self
    {
        $this->listenerPath = $listenerPath;
        return $this;
    }

    public function setInstance(?FlowInstanceModel $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    public function setExt(?string $ext): self
    {
        $this->ext = $ext;
        return $this;
    }

    public function setExtMap(array $extMap): self
    {
        $this->extMap = $extMap;
        return $this;
    }

    public function setNodeList(array $nodeList): self
    {
        $this->nodeList = $nodeList;
        return $this;
    }

    public function setChartStatusColor(array $chartStatusColor): self
    {
        $this->chartStatusColor = $chartStatusColor;
        return $this;
    }

    public function setTopText(?string $topText): self
    {
        $this->topText = $topText;
        return $this;
    }

    public function setTopTextShow(bool $topTextShow): self
    {
        $this->topTextShow = $topTextShow;
        return $this;
    }

    public function setCreateBy(?string $createBy): self
    {
        $this->createBy = $createBy;
        return $this;
    }

    public function setUpdateBy(?string $updateBy): self
    {
        $this->updateBy = $updateBy;
        return $this;
    }

    public function setCategoryList(array $categoryList): self
    {
        $this->categoryList = $categoryList;
        return $this;
    }

    public function setFormPathList(array $formPathList): self
    {
        $this->formPathList = $formPathList;
        return $this;
    }

    public static function copyCombine(DefJson $defJson): FlowCombine
    {
        $definition  = self::copyDefDto2Model($defJson);
        $flowCombine = new FlowCombine();
        $flowCombine->setDefinition($definition);
        $flowCombine->setAllNodes($definition->getNodeList());

        $skipList = collect($definition->getNodeList() ?? [])
            ->flatMap(function ($node) {
                return $node->getSkipList() ?? [];
            })
            ->all();

        $flowCombine->setAllSkips($skipList);
        return $flowCombine;
    }


    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

}
