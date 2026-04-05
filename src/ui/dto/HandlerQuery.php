<?php

namespace Yflow\ui\dto;


/**
 * 流程设计器-办理人权限设置列表查询参数
 * 办理人权限列表选择框，可能存在多个，比如：部门、角色、用户的情况
 *
 *
 */
class HandlerQuery
{
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
     * 办理权限类型，比如用户/角色/部门等
     * @var string|null
     */
    private ?string $handlerType = null;

    /**
     * 页面左侧树权限分组主键，如：角色、部门等主键
     * @var string|null
     */
    private ?string $groupId = null;

    /**
     * 当前页码
     * @var int|null
     */
    private ?int $pageNum = null;

    /**
     * 每页显示条数
     * @var int|null
     */
    private ?int $pageSize = null;

    /**
     * 开始时间
     * @var string|null
     */
    private ?string $beginTime = null;

    /**
     * 结束时间
     * @var string|null
     */
    private ?string $endTime = null;

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
     * 获取办理权限类型
     * @return string|null
     */
    public function getHandlerType(): ?string
    {
        return $this->handlerType;
    }

    /**
     * 设置办理权限类型
     * @param string|null $handlerType
     * @return $this
     */
    public function setHandlerType(?string $handlerType): self
    {
        $this->handlerType = $handlerType;
        return $this;
    }

    /**
     * 获取页面左侧树权限分组主键
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * 设置页面左侧树权限分组主键
     * @param string|null $groupId
     * @return $this
     */
    public function setGroupId(?string $groupId): self
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * 获取当前页码
     * @return int|null
     */
    public function getPageNum(): ?int
    {
        return $this->pageNum;
    }

    /**
     * 设置当前页码
     * @param int|null $pageNum
     * @return $this
     */
    public function setPageNum(?int $pageNum): self
    {
        $this->pageNum = $pageNum;
        return $this;
    }

    /**
     * 获取每页显示条数
     * @return int|null
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    /**
     * 设置每页显示条数
     * @param int|null $pageSize
     * @return $this
     */
    public function setPageSize(?int $pageSize): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * 获取开始时间
     * @return string|null
     */
    public function getBeginTime(): ?string
    {
        return $this->beginTime;
    }

    /**
     * 设置开始时间
     * @param string|null $beginTime
     * @return $this
     */
    public function setBeginTime(?string $beginTime): self
    {
        $this->beginTime = $beginTime;
        return $this;
    }

    /**
     * 获取结束时间
     * @return string|null
     */
    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    /**
     * 设置结束时间
     * @param string|null $endTime
     * @return $this
     */
    public function setEndTime(?string $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }
}
