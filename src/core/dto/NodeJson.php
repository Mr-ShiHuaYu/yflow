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

use Yflow\core\utils\MapUtil;


/**
 * 流程节点对象Vo
 *
 *
 * @since 2023-03-29
 */
class NodeJson
{

    /**
     * 节点类型（0开始节点 1中间节点 2结束节点 3互斥网关 4并行网关）
     */
    public ?int $nodeType = null;
    /**
     * 流程节点编码   每个流程的nodeCode是唯一的,即definitionId+nodeCode唯一,在数据库层面做了控制
     */
    public ?string $nodeCode = null;
    /**
     * 流程节点名称
     */
    public ?string $nodeName = null;
    /**
     * 流程节点版本
     */
    public ?string $version = null;
    /**
     * 权限标识（权限类型:权限标识，可以多个，用@@隔开)
     */
    public ?string $permissionFlag = null;
    /**
     * 流程签署比例值
     */
    public string|float|null $nodeRatio = null;
    /**
     * 流程节点坐标
     */
    public ?string $coordinate = null;
    /**
     * 任意结点跳转
     */
    public ?string $anyNodeSkip = null;
    /**
     * 监听器类型
     */
    public ?string $listenerType = null;
    /**
     * 监听器路径
     */
    public ?string $listenerPath = null;
    /**
     * 审批表单是否自定义（Y=是 N=否）
     */
    public ?string $formCustom = null;

    /**
     * 审批表单是否自定义（Y=是 N=否）
     */
    public ?string $formPath = null;

    /**
     * 节点扩展属性
     */
    public ?string $ext = null;

    /**
     * 办理状态: 0未办理 1待办理 2已办理
     */
    public ?int $status = null;

    /**
     * 扩展map，保存业务自定义扩展属性
     */
    public ?array $extMap = null;

    /**
     * 流程图节点提示内容
     */
    public ?PromptContent $promptContent = null;

    /**
     * @var SkipJson[]
     */
    public array $skipList = [];

    public ?string $createBy = null;

    public ?string $updateBy = null;

    /**
     * @return int|null
     */
    public function getNodeType(): ?int
    {
        return $this->nodeType;
    }

    /**
     * @param int|null $nodeType
     * @return self
     */
    public function setNodeType(?int $nodeType): self
    {
        $this->nodeType = $nodeType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNodeCode(): ?string
    {
        return $this->nodeCode;
    }

    /**
     * @param string|null $nodeCode
     * @return self
     */
    public function setNodeCode(?string $nodeCode): self
    {
        $this->nodeCode = $nodeCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNodeName(): ?string
    {
        return $this->nodeName;
    }

    /**
     * @param string|null $nodeName
     * @return self
     */
    public function setNodeName(?string $nodeName): self
    {
        $this->nodeName = $nodeName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param string|null $version
     * @return self
     */
    public function setVersion(?string $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPermissionFlag(): ?string
    {
        return $this->permissionFlag;
    }

    /**
     * @param string|null $permissionFlag
     * @return self
     */
    public function setPermissionFlag(?string $permissionFlag): self
    {
        $this->permissionFlag = $permissionFlag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNodeRatio(): ?string
    {
        return $this->nodeRatio;
    }

    /**
     * @param string|null $nodeRatio
     * @return self
     */
    public function setNodeRatio(?string $nodeRatio): self
    {
        $this->nodeRatio = $nodeRatio;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCoordinate(): ?string
    {
        return $this->coordinate;
    }

    /**
     * @param string|null $coordinate
     * @return self
     */
    public function setCoordinate(?string $coordinate): self
    {
        $this->coordinate = $coordinate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnyNodeSkip(): ?string
    {
        return $this->anyNodeSkip;
    }

    /**
     * @param string|null $anyNodeSkip
     * @return self
     */
    public function setAnyNodeSkip(?string $anyNodeSkip): self
    {
        $this->anyNodeSkip = $anyNodeSkip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getListenerType(): ?string
    {
        return $this->listenerType;
    }

    /**
     * @param string|null $listenerType
     * @return self
     */
    public function setListenerType(?string $listenerType): self
    {
        $this->listenerType = $listenerType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getListenerPath(): ?string
    {
        return $this->listenerPath;
    }

    /**
     * @param string|null $listenerPath
     * @return self
     */
    public function setListenerPath(?string $listenerPath): self
    {
        $this->listenerPath = $listenerPath;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormCustom(): ?string
    {
        return $this->formCustom;
    }

    /**
     * @param string|null $formCustom
     * @return self
     */
    public function setFormCustom(?string $formCustom): self
    {
        $this->formCustom = $formCustom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormPath(): ?string
    {
        return $this->formPath;
    }

    /**
     * @param string|null $formPath
     * @return self
     */
    public function setFormPath(?string $formPath): self
    {
        $this->formPath = $formPath;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExt(): ?string
    {
        return $this->ext;
    }

    /**
     * @param string|null $ext
     * @return self
     */
    public function setExt(?string $ext): self
    {
        $this->ext = $ext;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     * @return self
     */
    public function setStatus(?int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtMap(): array
    {
        if (MapUtil::isEmpty($this->extMap)) {
            $this->extMap = [];
        }
        return $this->extMap;
    }

    /**
     * @param array|null $extMap
     * @return self
     */
    public function setExtMap(?array $extMap): self
    {
        $this->extMap = $extMap;
        return $this;
    }

    /**
     * @return PromptContent|null
     */
    public function getPromptContent(): ?PromptContent
    {
        return $this->promptContent;
    }

    /**
     * @param PromptContent|null $promptContent
     * @return self
     */
    public function setPromptContent(?PromptContent $promptContent): self
    {
        $this->promptContent = $promptContent;
        return $this;
    }

    /**
     * @return array
     */
    public function getSkipList(): array
    {
        return $this->skipList;
    }

    /**
     * @param array $skipList
     * @return self
     */
    public function setSkipList(array $skipList): self
    {
        $this->skipList = $skipList;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    /**
     * @param string|null $createBy
     * @return self
     */
    public function setCreateBy(?string $createBy): self
    {
        $this->createBy = $createBy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    /**
     * @param string|null $updateBy
     * @return self
     */
    public function setUpdateBy(?string $updateBy): self
    {
        $this->updateBy = $updateBy;
        return $this;
    }
}
