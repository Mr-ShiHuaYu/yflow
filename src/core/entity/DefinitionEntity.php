<?php

namespace Yflow\core\entity;


/**
 * 流程定义对象 flow_definition
 *
 *
 * @since 2023-03-29
 */
class DefinitionEntity
{
    /**
     * 主键
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 创建时间
     * @var string|null
     */
    public ?string $createTime = null;

    /**
     * 更新时间
     * @var string|null
     */
    public ?string $updateTime = null;

    /**
     * 创建人
     * @var string|null
     */
    public ?string $createBy = null;

    /**
     * 更新人
     * @var string|null
     */
    public ?string $updateBy = null;

    /**
     * 租户ID
     * @var string|null
     */
    public ?string $tenantId = null;

    /**
     * 删除标记
     * @var string|null
     */
    public ?string $delFlag = null;

    /**
     * 流程编码
     * @var string|null
     */
    public ?string $flowCode = null;

    /**
     * 流程名称
     * @var string|null
     */
    public ?string $flowName = null;

    /**
     * 设计器模型（CLASSICS经典模型 MIMIC仿钉钉模型）
     * @var string|null
     */
    public ?string $modelValue = null;

    /**
     * 流程类别
     * @var string|null
     */
    public ?string $category = null;

    /**
     * 流程版本
     * @var string|null
     */
    public ?string $version = null;

    /**
     * 是否发布（0未开启 1开启）
     * @var int|null
     */
    public ?int $isPublish = null;

    /**
     * 审批表单是否自定义（Y是 N否）
     * @var string|null
     */
    public ?string $formCustom = null;

    /**
     * 审批表单路径
     * @var string|null
     */
    public ?string $formPath = null;

    /**
     * 流程激活状态（0挂起 1激活）
     * @var int|null
     */
    public ?int $activityStatus = null;

    /**
     * 监听器类型
     * @var string|null
     */
    public ?string $listenerType = null;

    /**
     * 监听器路径
     * @var string|null
     */
    public ?string $listenerPath = null;

    /**
     * 扩展字段，预留给业务系统使用
     * @var string|null
     */
    public ?string $ext = null;

    /**
     * 节点列表
     * @var array|null
     */
    public ?array $nodeList = null;

    /**
     * 流程权限人
     * @var array|null
     */
    public ?array $userList = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreateTime(): ?string
    {
        return $this->createTime;
    }

    /**
     * @param string $createTime
     * @return $this
     */
    public function setCreateTime(string $createTime): static
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdateTime(): ?string
    {
        return $this->updateTime;
    }

    /**
     * @param string $updateTime
     * @return $this
     */
    public function setUpdateTime(string $updateTime): static
    {
        $this->updateTime = $updateTime;
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
     * @param string $createBy
     * @return $this
     */
    public function setCreateBy(string $createBy): static
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
     * @param string $updateBy
     * @return $this
     */
    public function setUpdateBy(string $updateBy): static
    {
        $this->updateBy = $updateBy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTenantId(): ?string
    {
        return $this->tenantId;
    }

    /**
     * @param string $tenantId
     * @return $this
     */
    public function setTenantId(string $tenantId): static
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDelFlag(): ?string
    {
        return $this->delFlag;
    }

    /**
     * @param string $delFlag
     * @return $this
     */
    public function setDelFlag(string $delFlag): static
    {
        $this->delFlag = $delFlag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFlowCode(): ?string
    {
        return $this->flowCode;
    }

    /**
     * @param string $flowCode
     * @return $this
     */
    public function setFlowCode(string $flowCode): static
    {
        $this->flowCode = $flowCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFlowName(): ?string
    {
        return $this->flowName;
    }

    /**
     * @param string $flowName
     * @return $this
     */
    public function setFlowName(string $flowName): static
    {
        $this->flowName = $flowName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getModelValue(): ?string
    {
        return $this->modelValue;
    }

    /**
     * @param string $modelValue
     * @return $this
     */
    public function setModelValue(string $modelValue): static
    {
        $this->modelValue = $modelValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory(string $category): static
    {
        $this->category = $category;
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
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version): static
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIsPublish(): ?int
    {
        return $this->isPublish;
    }

    /**
     * @param int $isPublish
     * @return $this
     */
    public function setIsPublish(int $isPublish): static
    {
        $this->isPublish = $isPublish;
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
     * @param string $formCustom
     * @return $this
     */
    public function setFormCustom(string $formCustom): static
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
     * @param string $formPath
     * @return $this
     */
    public function setFormPath(string $formPath): static
    {
        $this->formPath = $formPath;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getActivityStatus(): ?int
    {
        return $this->activityStatus;
    }

    /**
     * @param int $activityStatus
     * @return $this
     */
    public function setActivityStatus(int $activityStatus): static
    {
        $this->activityStatus = $activityStatus;
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
     * @param string $listenerType
     * @return $this
     */
    public function setListenerType(string $listenerType): static
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
     * @param string $listenerPath
     * @return $this
     */
    public function setListenerPath(string $listenerPath): static
    {
        $this->listenerPath = $listenerPath;
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
     * @param string $ext
     * @return $this
     */
    public function setExt(string $ext): static
    {
        $this->ext = $ext;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getNodeList(): ?array
    {
        return $this->nodeList;
    }

    /**
     * @param array $nodeList
     * @return $this
     */
    public function setNodeList(array $nodeList): static
    {
        $this->nodeList = $nodeList;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getUserList(): ?array
    {
        return $this->userList;
    }

    /**
     * @param array $userList
     * @return $this
     */
    public function setUserList(array $userList): static
    {
        $this->userList = $userList;
        return $this;
    }
}
