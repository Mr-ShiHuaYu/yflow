<?php

namespace Yflow\ui\dto;

use Closure;


/**
 * 办理人权限设置列表Function
 *
 *
 */
class HandlerFunDto
{
    /**
     * 权限列表
     * @var array|null
     */
    private ?array $list;

    /**
     * 权限列表list总数
     * @var int
     */
    private int $total;

    /**
     * 获取入库主键集合Function
     * @var Closure|null
     */
    private ?Closure $storageId = null;

    /**
     * 获取权限编码Function
     * @var Closure|null
     */
    private ?Closure $handlerCode = null;

    /**
     * 获取权限名称Function
     * @var Closure|null
     */
    private ?Closure $handlerName = null;

    /**
     * 获取权限分组名称Function
     * @var Closure|null
     */
    private ?Closure $groupName = null;

    /**
     * 获取创建时间Function
     * @var Closure|null
     */
    private ?Closure $createTime = null;

    /**
     * 构造函数
     * @param array $list
     * @param int $total
     */
    public function __construct(array $list, int $total)
    {
        $this->list = $list;
        $this->total = $total;
    }

    /**
     * 获取权限列表
     * @return array|null
     */
    public function getList(): ?array
    {
        return $this->list;
    }

    /**
     * 设置权限列表
     * @param array|null $list
     * @return $this
     */
    public function setList(?array $list): self
    {
        $this->list = $list;
        return $this;
    }

    /**
     * 获取权限列表总数
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * 设置权限列表总数
     * @param int $total
     * @return $this
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    /**
     * 获取入库主键集合Function
     * @return callable|null
     */
    public function getStorageId(): ?callable
    {
        return $this->storageId;
    }

    /**
     * 设置入库主键集合Function
     * @param callable|null $storageId
     * @return $this
     */
    public function setStorageId(?callable $storageId): self
    {
        $this->storageId = $storageId;
        return $this;
    }

    /**
     * 获取权限编码Function
     * @return callable|null
     */
    public function getHandlerCode(): ?callable
    {
        return $this->handlerCode;
    }

    /**
     * 设置权限编码Function
     * @param callable|null $handlerCode
     * @return $this
     */
    public function setHandlerCode(?callable $handlerCode): self
    {
        $this->handlerCode = $handlerCode;
        return $this;
    }

    /**
     * 获取权限名称Function
     * @return callable|null
     */
    public function getHandlerName(): ?callable
    {
        return $this->handlerName;
    }

    /**
     * 设置权限名称Function
     * @param callable|null $handlerName
     * @return $this
     */
    public function setHandlerName(?callable $handlerName): self
    {
        $this->handlerName = $handlerName;
        return $this;
    }

    /**
     * 获取权限分组名称Function
     * @return callable|null
     */
    public function getGroupName(): ?callable
    {
        return $this->groupName;
    }

    /**
     * 设置权限分组名称Function
     * @param callable|null $groupName
     * @return $this
     */
    public function setGroupName(?callable $groupName): self
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * 获取创建时间Function
     * @return callable|null
     */
    public function getCreateTime(): ?callable
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间Function
     * @param callable|null $createTime
     * @return $this
     */
    public function setCreateTime(?callable $createTime): self
    {
        $this->createTime = $createTime;
        return $this;
    }
}
