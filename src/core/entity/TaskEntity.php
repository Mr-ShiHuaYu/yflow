<?php

namespace Yflow\core\entity;


/**
 * 待办任务记录对象 flow_task
 *
 *
 * @since 2023-03-29
 */
class TaskEntity
{
    /**
     * 主键
     * @var int
     */
    public $id;

    /**
     * 创建时间
     * @var string
     */
    public $createTime;

    /**
     * 更新时间
     * @var string
     */
    public $updateTime;

    /**
     * 创建人
     * @var string
     */
    public $createBy;

    /**
     * 更新人
     * @var string
     */
    public $updateBy;

    /**
     * 租户ID
     * @var string
     */
    public $tenantId;

    /**
     * 删除标记
     * @var string
     */
    public $delFlag;

    /**
     * 对应flow_definition表的id
     * @var int
     */
    public $definitionId;

    /**
     * 流程实例表id
     * @var int
     */
    public $instanceId;

    /**
     * 流程名称
     * @var string
     */
    public $flowName;

    /**
     * 业务id
     * @var string
     */
    public $businessId;

    /**
     * 节点编码
     * @var string
     */
    public $nodeCode;

    /**
     * 节点名称
     * @var string
     */
    public $nodeName;

    /**
     * 节点类型（0开始节点 1中间节点 2结束节点 3互斥网关 4并行网关）
     * @var int
     */
    public $nodeType;

    /**
     * 流程状态（0待提交 1审批中 2审批通过 4终止 5作废 6撤销 8已完成 9已退回 10失效 11拿回）
     * @see org.dromara.warm.flow.core.enums.FlowStatus
     * @var string
     */
    public $flowStatus;

    /**
     * 权限标识 permissionFlag的list形式
     * @var array
     */
    public $permissionList;

    /**
     * 流程用户列表
     * @var array
     */
    public $userList;

    /**
     * 审批表单是否自定义（Y=是 N=否）
     * @var string
     */
    public $formCustom;

    /**
     * 审批表单
     * @var string
     */
    public $formPath;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param string $createTime
     * @return $this
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param string $updateTime
     * @return $this
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateBy()
    {
        return $this->createBy;
    }

    /**
     * @param string $createBy
     * @return $this
     */
    public function setCreateBy($createBy)
    {
        $this->createBy = $createBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdateBy()
    {
        return $this->updateBy;
    }

    /**
     * @param string $updateBy
     * @return $this
     */
    public function setUpdateBy($updateBy)
    {
        $this->updateBy = $updateBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getTenantId()
    {
        return $this->tenantId;
    }

    /**
     * @param string $tenantId
     * @return $this
     */
    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDelFlag()
    {
        return $this->delFlag;
    }

    /**
     * @param string $delFlag
     * @return $this
     */
    public function setDelFlag($delFlag)
    {
        $this->delFlag = $delFlag;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefinitionId()
    {
        return $this->definitionId;
    }

    /**
     * @param int $definitionId
     * @return $this
     */
    public function setDefinitionId($definitionId)
    {
        $this->definitionId = $definitionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * @param int $instanceId
     * @return $this
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlowName()
    {
        return $this->flowName;
    }

    /**
     * @param string $flowName
     * @return $this
     */
    public function setFlowName($flowName)
    {
        $this->flowName = $flowName;
        return $this;
    }

    /**
     * @return string
     */
    public function getBusinessId()
    {
        return $this->businessId;
    }

    /**
     * @param string $businessId
     * @return $this
     */
    public function setBusinessId($businessId)
    {
        $this->businessId = $businessId;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeCode()
    {
        return $this->nodeCode;
    }

    /**
     * @param string $nodeCode
     * @return $this
     */
    public function setNodeCode($nodeCode)
    {
        $this->nodeCode = $nodeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param string $nodeName
     * @return $this
     */
    public function setNodeName($nodeName)
    {
        $this->nodeName = $nodeName;
        return $this;
    }

    /**
     * @return int
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }

    /**
     * @param int $nodeType
     * @return $this
     */
    public function setNodeType($nodeType)
    {
        $this->nodeType = $nodeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlowStatus()
    {
        return $this->flowStatus;
    }

    /**
     * @param string $flowStatus
     * @return $this
     */
    public function setFlowStatus($flowStatus)
    {
        $this->flowStatus = $flowStatus;
        return $this;
    }

    /**
     * @return array
     */
    public function getPermissionList()
    {
        return $this->permissionList;
    }

    /**
     * @param array $permissionList
     * @return $this
     */
    public function setPermissionList($permissionList)
    {
        $this->permissionList = $permissionList;
        return $this;
    }

    /**
     * @return array
     */
    public function getUserList()
    {
        return $this->userList;
    }

    /**
     * @param array $userList
     * @return $this
     */
    public function setUserList($userList)
    {
        $this->userList = $userList;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormCustom()
    {
        return $this->formCustom;
    }

    /**
     * @param string $formCustom
     * @return $this
     */
    public function setFormCustom($formCustom)
    {
        $this->formCustom = $formCustom;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormPath()
    {
        return $this->formPath;
    }

    /**
     * @param string $formPath
     * @return $this
     */
    public function setFormPath($formPath)
    {
        $this->formPath = $formPath;
        return $this;
    }
}
