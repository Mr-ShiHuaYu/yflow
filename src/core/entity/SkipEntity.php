<?php

namespace Yflow\core\entity;


/**
 * 节点跳转关联对象 flow_skip
 *
 *
 * @since 2023-03-29
 */
class SkipEntity
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
     * 流程id
     * @var int
     */
    public $definitionId;

    /**
     * 节点id
     * @var int
     */
    public $nodeId;

    /**
     * 当前流程节点的编码
     * @var string
     */
    public $nowNodeCode;

    /**
     * 当前节点类型（0开始节点 1中间节点 2结束节点 3互斥网关 4并行网关）
     * @var int
     */
    public $nowNodeType;

    /**
     * 下一个流程节点的编码
     * @var string
     */
    public $nextNodeCode;

    /**
     * 下一个节点类型（0开始节点 1中间节点 2结束节点 3互斥网关 4并行网关）
     * @var int
     */
    public $nextNodeType;

    /**
     * 跳转名称
     * @var string
     */
    public $skipName;

    /**
     * 跳转类型（PASS审批通过 REJECT退回）
     * @var string
     */
    public $skipType;

    /**
     * 跳转条件
     * @var string
     */
    public $skipCondition;

    /**
     * 流程跳转坐标
     * @var string
     */
    public $coordinate;

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
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * @param int $nodeId
     * @return $this
     */
    public function setNodeId($nodeId)
    {
        $this->nodeId = $nodeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getNowNodeCode()
    {
        return $this->nowNodeCode;
    }

    /**
     * @param string $nowNodeCode
     * @return $this
     */
    public function setNowNodeCode($nowNodeCode)
    {
        $this->nowNodeCode = $nowNodeCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getNowNodeType()
    {
        return $this->nowNodeType;
    }

    /**
     * @param int $nowNodeType
     * @return $this
     */
    public function setNowNodeType($nowNodeType)
    {
        $this->nowNodeType = $nowNodeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextNodeCode()
    {
        return $this->nextNodeCode;
    }

    /**
     * @param string $nextNodeCode
     * @return $this
     */
    public function setNextNodeCode($nextNodeCode)
    {
        $this->nextNodeCode = $nextNodeCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getNextNodeType()
    {
        return $this->nextNodeType;
    }

    /**
     * @param int $nextNodeType
     * @return $this
     */
    public function setNextNodeType($nextNodeType)
    {
        $this->nextNodeType = $nextNodeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkipName()
    {
        return $this->skipName;
    }

    /**
     * @param string $skipName
     * @return $this
     */
    public function setSkipName($skipName)
    {
        $this->skipName = $skipName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkipType()
    {
        return $this->skipType;
    }

    /**
     * @param string $skipType
     * @return $this
     */
    public function setSkipType($skipType)
    {
        $this->skipType = $skipType;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkipCondition()
    {
        return $this->skipCondition;
    }

    /**
     * @param string $skipCondition
     * @return $this
     */
    public function setSkipCondition($skipCondition)
    {
        $this->skipCondition = $skipCondition;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordinate()
    {
        return $this->coordinate;
    }

    /**
     * @param string $coordinate
     * @return $this
     */
    public function setCoordinate($coordinate)
    {
        $this->coordinate = $coordinate;
        return $this;
    }
}
