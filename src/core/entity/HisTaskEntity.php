<?php

namespace Yflow\core\entity;


/**
 * 历史任务记录对象 flow_his_task
 *
 *
 * @since 2023-03-29
 */
class HisTaskEntity
{
    /**
     * 主键
     * @var int
     */
    public int $id;

    /**
     * 任务开始时间
     * @var string
     */
    public string $createTime;

    /**
     * 审批完成时间
     * @var string
     */
    public string $updateTime;

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
     * 流程实例表id
     * @var int
     */
    public int $instanceId;

    /**
     * 任务表id
     * @var int
     */
    public int $taskId;

    /**
     * 协作方式(1审批 2转办 3委派 4会签 5票签 6加签 7减签)
     * @var int
     */
    public int $cooperateType;

    /**
     * 业务id
     * @var string
     */
    public string $businessId;

    /**
     * 开始节点编码
     * @var string
     */
    public string $nodeCode;

    /**
     * 开始节点名称
     * @var string
     */
    public string $nodeName;

    /**
     * 开始节点类型（0开始节点 1中间节点 2结束节点 3互斥网关 4并行网关）
     * @var int
     */
    public int $nodeType;

    /**
     * 目标节点编码
     * @var string
     */
    public string $targetNodeCode;

    /**
     * 结束节点名称
     * @var string
     */
    public string $targetNodeName;

    /**
     * 审批者
     * @var string
     */
    public string $approver;

    /**
     * 协作人(只有转办、会签、票签、委派)
     * @var string
     */
    public string $collaborator;

    /**
     * 权限标识 permissionFlag的list形式
     * @var array
     */
    public array $permissionList;

    /**
     * 跳转类型（PASS通过 REJECT退回 NONE无动作）
     * @var string
     */
    public string $skipType;

    /**
     * 流程状态（0待提交 1审批中 2审批通过 4终止 5作废 6撤销 8已完成 9已退回 10失效 11拿回）
     * @var string
     */
    public string $flowStatus;

    /**
     * 审批意见
     * @var string
     */
    public string $message;

    /**
     * 流程变量
     * @var string
     */
    public string $variable;

    /**
     * 业务详情 存业务类的json
     * @var string
     */
    public string $ext;

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
    public function setId(int $id): static
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
    public function setCreateTime(string $createTime): static
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
    public function setUpdateTime(string $updateTime): static
    {
        $this->updateTime = $updateTime;
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
    public function setTenantId(string $tenantId): static
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
     * @return int
     */
    public function getInstanceId(): int
    {
        return $this->instanceId;
    }

    /**
     * @param int $instanceId
     * @return $this
     */
    public function setInstanceId($instanceId): static
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->taskId;
    }

    /**
     * @param int $taskId
     * @return $this
     */
    public function setTaskId($taskId): static
    {
        $this->taskId = $taskId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCooperateType(): int
    {
        return $this->cooperateType;
    }

    /**
     * @param int $cooperateType
     * @return $this
     */
    public function setCooperateType($cooperateType): static
    {
        $this->cooperateType = $cooperateType;
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
    public function setNodeCode(string $nodeCode): static
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
    public function setNodeType(int $nodeType): static
    {
        $this->nodeType = $nodeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetNodeCode(): string
    {
        return $this->targetNodeCode;
    }

    /**
     * @param string $targetNodeCode
     * @return $this
     */
    public function setTargetNodeCode(string $targetNodeCode): static
    {
        $this->targetNodeCode = $targetNodeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetNodeName(): string
    {
        return $this->targetNodeName;
    }

    /**
     * @param string $targetNodeName
     * @return $this
     */
    public function setTargetNodeName($targetNodeName): static
    {
        $this->targetNodeName = $targetNodeName;
        return $this;
    }

    /**
     * @return string
     */
    public function getApprover(): string
    {
        return $this->approver;
    }

    /**
     * @param string $approver
     * @return $this
     */
    public function setApprover($approver): static
    {
        $this->approver = $approver;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollaborator(): string
    {
        return $this->collaborator;
    }

    /**
     * @param string $collaborator
     * @return $this
     */
    public function setCollaborator(string $collaborator): static
    {
        $this->collaborator = $collaborator;
        return $this;
    }

    /**
     * @return array
     */
    public function getPermissionList(): array
    {
        return $this->permissionList;
    }

    /**
     * @param array $permissionList
     * @return $this
     */
    public function setPermissionList($permissionList): static
    {
        $this->permissionList = $permissionList;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkipType(): string
    {
        return $this->skipType;
    }

    /**
     * @param string $skipType
     * @return $this
     */
    public function setSkipType($skipType): static
    {
        $this->skipType = $skipType;
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
    public function setFlowStatus(string $flowStatus): static
    {
        $this->flowStatus = $flowStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message): static
    {
        $this->message = $message;
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
    public function setFormPath(string $formPath): static
    {
        $this->formPath = $formPath;
        return $this;
    }
}
