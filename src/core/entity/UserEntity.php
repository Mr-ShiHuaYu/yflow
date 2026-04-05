<?php

namespace Yflow\core\entity;


/**
 * 流程用户对象 flow_user
 *
 * @author xiarg
 * @since 2024/5/10 10:58
 */
class UserEntity
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
     * 创建人：比如作为委托的人保存
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
     * 人员类型（1待办任务的审批人权限 2待办任务的转办人权限 3待办任务的委托人权限）
     * @var string
     */
    public $type;

    /**
     * 权限人
     * @var string
     */
    public $processedBy;

    /**
     * 任务表ID
     * @var int
     */
    public $associated;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getProcessedBy()
    {
        return $this->processedBy;
    }

    /**
     * @param string $processedBy
     * @return $this
     */
    public function setProcessedBy($processedBy)
    {
        $this->processedBy = $processedBy;
        return $this;
    }

    /**
     * @return int
     */
    public function getAssociated()
    {
        return $this->associated;
    }

    /**
     * @param int $associated
     * @return $this
     */
    public function setAssociated($associated)
    {
        $this->associated = $associated;
        return $this;
    }
}
