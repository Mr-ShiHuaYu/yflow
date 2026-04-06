<?php

namespace Yflow\ui\vo;

use JsonSerializable;

/**
 * 流程设计器-办理人权限设置列表
 * 办理人权限列表选择框，可能存在多个，比如：部门、角色、用户的情况
 *
 *
 */
class HandlerAuth implements JsonSerializable
{
    /**
     * 入库主键，比如怕角色和用户id重复，可拼接为role:id
     * @var string|null
     */
    private ?string $storageId = null;

    /**
     * 权限编码，如：zhang、roleAdmin、deptAdmin等编码
     * @var string|null
     */
    private ?string $handlerCode = null;

    /**
     * 权限名称，如：管理员、角色管理员、部门管理员等名称
     * @var string|null
     */
    private ?string $handlerName = null;

    /**
     * 权限分组名称，如：角色、部门等名称
     * @var string|null
     */
    private ?string $groupName = null;

    /**
     * 创建时间
     * @var string|null
     */
    private ?string $createTime = null;

    /**
     * 获取入库主键
     * @return string|null
     */
    public function getStorageId(): ?string
    {
        return $this->storageId;
    }

    /**
     * 设置入库主键
     * @param string|null $storageId
     * @return $this
     */
    public function setStorageId(?string $storageId): self
    {
        $this->storageId = $storageId;
        return $this;
    }

    /**
     * 获取权限编码
     * @return string|null
     */
    public function getHandlerCode(): ?string
    {
        return $this->handlerCode;
    }

    /**
     * 设置权限编码
     * @param string|null $handlerCode
     * @return $this
     */
    public function setHandlerCode(?string $handlerCode): self
    {
        $this->handlerCode = $handlerCode;
        return $this;
    }

    /**
     * 获取权限名称
     * @return string|null
     */
    public function getHandlerName(): ?string
    {
        return $this->handlerName;
    }

    /**
     * 设置权限名称
     * @param string|null $handlerName
     * @return $this
     */
    public function setHandlerName(?string $handlerName): self
    {
        $this->handlerName = $handlerName;
        return $this;
    }

    /**
     * 获取权限分组名称
     * @return string|null
     */
    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    /**
     * 设置权限分组名称
     * @param string|null $groupName
     * @return $this
     */
    public function setGroupName(?string $groupName): self
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * 获取创建时间
     * @return string|null
     */
    public function getCreateTime(): ?string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     * @param string|null $createTime
     * @return $this
     */
    public function setCreateTime(?string $createTime): self
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * 实现 JsonSerializable 接口
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'storageId'   => $this->storageId,
            'handlerCode' => $this->handlerCode,
            'handlerName' => $this->handlerName,
            'groupName'   => $this->groupName,
            'createTime'  => $this->createTime,
        ];
    }
}
