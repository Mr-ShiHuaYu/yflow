<?php

namespace Yflow\core\entity;


/**
 * 流程实例对象 flow_instance
 *
 *
 * @since 2023-03-29
 */
class InstanceEntity
{
    /**
     * 主键
     * @var int
     */
    public int $id;

    /**
     * 创建时间
     * @var string
     */
    public string $createTime;

    /**
     * 更新时间
     * @var string
     */
    public string $updateTime;

    /**
     * 创建人
     * @var string
     */
    public string $createBy;

    /**
     * 更新人
     * @var string
     */
    public string $updateBy;

    /**
     * 租户ID
     * @var string
     */
    public string $tenantId;

    /**
     * 删除标记
     * @var string
     */
    public string $delFlag;

    /**
     * 对应flow_definition表的id
     * @var int
     */
    public int $definitionId;

    /**
     * 流程名称
     * @var string
     */
    public string $flowName;

    /**
     * 业务ID
     * @var string
     */
    public string $businessId;

    /**
     * @see org.dromara.warm.flow.core.enums.NodeType
     * 节点类型（0开始节点 1中间节点 2结束节点 3互斥网关 4并行网关）
     * @var int
     */
    public int $nodeType;

    /**
     * 流程节点编码   每个流程的nodeCode是唯一的,即definitionId+nodeCode唯一,在数据库层面做了控制
     * @var string
     */
    public string $nodeCode;

    /**
     * 流程节点名称
     * @var string
     */
    public string $nodeName;

    /**
     * 流程变量
     * @var string
     */
    public string $variable;

    /**
     * @see org.dromara.warm.flow.core.enums.FlowStatus
     * 流程状态（0待提交 1审批中 2审批通过 4终止 5作废 6撤销 8已完成 9已退回 10失效 11拿回）
     * @var string
     */
    public string $flowStatus;

    /**
     * @see org.dromara.warm.flow.core.enums.ActivityStatus
     * 流程激活状态（0挂起 1激活）
     * @var int
     */
    public int $activityStatus;

    /**
     * 审批表单是否自定义（Y=是 N=否）
     * @var string
     */
    public string $formCustom;

    /**
     * 审批表单是否自定义（Y=是 N=否）
     * @var string
     */
    public string $formPath;

    /**
     * 流程定义json
     * @var string
     */
    public string $defJson;

    /**
     * 扩展字段，预留给业务系统使用
     * @var string
     */
    public string $ext;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * @param string $createTime
     * @return $this
     */
    public function setCreateTime($createTime): static
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * @param string $updateTime
     * @return $this
     */
    public function setUpdateTime($updateTime): static
    {
        $this->updateTime = $updateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateBy(): string
    {
        return $this->createBy;
    }

    /**
     * @param string $createBy
     * @return $this
     */
    public function setCreateBy($createBy): static
    {
        $this->createBy = $createBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdateBy(): string
    {
        return $this->updateBy;
    }

    /**
     * @param string $updateBy
     * @return $this
     */
    public function setUpdateBy($updateBy): static
    {
        $this->updateBy = $updateBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    /**
     * @param string $tenantId
     * @return $this
     */
    public function setTenantId($tenantId): static
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDelFlag(): string
    {
        return $this->delFlag;
    }

    /**
     * @param string $delFlag
     * @return $this
     */
    public function setDelFlag($delFlag): static
    {
        $this->delFlag = $delFlag;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefinitionId(): int
    {
        return $this->definitionId;
    }

    /**
     * @param int $definitionId
     * @return $this
     */
    public function setDefinitionId($definitionId): static
    {
        $this->definitionId = $definitionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlowName(): string
    {
        return $this->flowName;
    }

    /**
     * @param string $flowName
     * @return $this
     */
    public function setFlowName($flowName): static
    {
        $this->flowName = $flowName;
        return $this;
    }

    /**
     * @return string
     */
    public function getBusinessId(): string
    {
        return $this->businessId;
    }

    /**
     * @param string $businessId
     * @return $this
     */
    public function setBusinessId($businessId): static
    {
        $this->businessId = $businessId;
        return $this;
    }

    /**
     * @return int
     */
    public function getNodeType(): int
    {
        return $this->nodeType;
    }

    /**
     * @param int $nodeType
     * @return $this
     */
    public function setNodeType($nodeType): static
    {
        $this->nodeType = $nodeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeCode(): string
    {
        return $this->nodeCode;
    }

    /**
     * @param string $nodeCode
     * @return $this
     */
    public function setNodeCode($nodeCode): static
    {
        $this->nodeCode = $nodeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeName(): string
    {
        return $this->nodeName;
    }

    /**
     * @param string $nodeName
     * @return $this
     */
    public function setNodeName($nodeName): static
    {
        $this->nodeName = $nodeName;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariable(): string
    {
        return $this->variable;
    }

    /**
     * @param string $variable
     * @return $this
     */
    public function setVariable($variable): static
    {
        $this->variable = $variable;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlowStatus(): string
    {
        return $this->flowStatus;
    }

    /**
     * @param string $flowStatus
     * @return $this
     */
    public function setFlowStatus($flowStatus): static
    {
        $this->flowStatus = $flowStatus;
        return $this;
    }

    /**
     * @return int
     */
    public function getActivityStatus(): int
    {
        return $this->activityStatus;
    }

    /**
     * @param int $activityStatus
     * @return $this
     */
    public function setActivityStatus($activityStatus): static
    {
        $this->activityStatus = $activityStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormCustom(): string
    {
        return $this->formCustom;
    }

    /**
     * @param string $formCustom
     * @return $this
     */
    public function setFormCustom($formCustom): static
    {
        $this->formCustom = $formCustom;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormPath(): string
    {
        return $this->formPath;
    }

    /**
     * @param string $formPath
     * @return $this
     */
    public function setFormPath($formPath): static
    {
        $this->formPath = $formPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefJson(): string
    {
        return $this->defJson;
    }

    /**
     * @param string $defJson
     * @return $this
     */
    public function setDefJson($defJson): static
    {
        $this->defJson = $defJson;
        return $this;
    }

    /**
     * @return string
     */
    public function getExt(): string
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     * @return $this
     */
    public function setExt($ext): static
    {
        $this->ext = $ext;
        return $this;
    }
}
