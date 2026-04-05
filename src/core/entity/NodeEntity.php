<?php

namespace Yflow\core\entity;


/**
 * 流程节点对象 flow_node
 *
 *
 * @since 2023-03-29
 */
class NodeEntity
{
    /**
     * 跳转条件
     * @var array
     */
    public array $skipList = [];

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
     * 节点类型（0开始节点 1中间节点 2结束节点 3互斥网关 4并行网关）
     * @var int
     */
    public int $nodeType;

    /**
     * 流程id
     * @var int
     */
    public int $definitionId;

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
     * 权限标识（权限类型:权限标识，可以多个，用@@隔开)
     * @var string
     */
    public string $permissionFlag;

    /**
     * 流程签署比例值
     * @var string
     */
    public string $nodeRatio;

    /**
     * 流程节点坐标
     * @var string
     */
    public string $coordinate;

    /**
     * 版本
     *
     * @deprecated 下个版本废弃
     * @var string
     */
    public string $version;

    /**
     * 任意结点跳转
     * @var string
     */
    public string $anyNodeSkip;

    /**
     * 监听器类型
     * @var string
     */
    public string $listenerType;

    /**
     * 监听器路径
     * @var string
     */
    public string $listenerPath;

    /**
     * 审批表单是否自定义（Y=是 N=否）
     * @var string
     */
    public string $formCustom;

    /**
     * 审批表单路径
     * @var string
     */
    public string $formPath;

    /**
     * 节点扩展属性
     * @var string
     */
    public string $ext;

    /**
     * @return array
     */
    public function getSkipList(): array
    {
        return $this->skipList;
    }

    /**
     * @param array $skipList
     * @return $this
     */
    public function setSkipList($skipList): static
    {
        $this->skipList = $skipList;
        return $this;
    }

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
    public function getPermissionFlag(): string
    {
        return $this->permissionFlag;
    }

    /**
     * @param string $permissionFlag
     * @return $this
     */
    public function setPermissionFlag($permissionFlag): static
    {
        $this->permissionFlag = $permissionFlag;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeRatio(): string
    {
        return $this->nodeRatio;
    }

    /**
     * @param string $nodeRatio
     * @return $this
     */
    public function setNodeRatio($nodeRatio): static
    {
        $this->nodeRatio = $nodeRatio;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordinate(): string
    {
        return $this->coordinate;
    }

    /**
     * @param string $coordinate
     * @return $this
     */
    public function setCoordinate($coordinate): static
    {
        $this->coordinate = $coordinate;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion($version): static
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnyNodeSkip(): string
    {
        return $this->anyNodeSkip;
    }

    /**
     * @param string $anyNodeSkip
     * @return $this
     */
    public function setAnyNodeSkip($anyNodeSkip): static
    {
        $this->anyNodeSkip = $anyNodeSkip;
        return $this;
    }

    /**
     * @return string
     */
    public function getListenerType(): string
    {
        return $this->listenerType;
    }

    /**
     * @param string $listenerType
     * @return $this
     */
    public function setListenerType($listenerType): static
    {
        $this->listenerType = $listenerType;
        return $this;
    }

    /**
     * @return string
     */
    public function getListenerPath(): string
    {
        return $this->listenerPath;
    }

    /**
     * @param string $listenerPath
     * @return $this
     */
    public function setListenerPath($listenerPath): static
    {
        $this->listenerPath = $listenerPath;
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
