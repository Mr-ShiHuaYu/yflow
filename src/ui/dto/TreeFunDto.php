<?php

namespace Yflow\ui\dto;

use Closure;


/**
 * 页面左侧树列表Function
 *
 *
 */
class TreeFunDto
{
    /**
     * 左侧树列表
     * @var array|null
     */
    private ?array $list;

    /**
     * 获取左侧树ID Function
     * @var Closure|null
     */
    private ?Closure $id = null;

    /**
     * 获取左侧树名称 Function
     * @var Closure|null
     */
    private ?Closure $name = null;

    /**
     * 获取左侧树父级ID Function
     * @var Closure|null
     */
    private ?Closure $parentId = null;

    /**
     * 构造函数
     * @param array $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * 获取左侧树列表
     * @return array|null
     */
    public function getList(): ?array
    {
        return $this->list;
    }

    /**
     * 设置左侧树列表
     * @param array|null $list
     * @return $this
     */
    public function setList(?array $list): self
    {
        $this->list = $list;
        return $this;
    }

    /**
     * 获取左侧树ID Function
     * @return callable|null
     */
    public function getId(): ?callable
    {
        return $this->id;
    }

    /**
     * 设置左侧树ID Function
     * @param callable|null $id
     * @return $this
     */
    public function setId(?callable $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * 获取左侧树名称 Function
     * @return callable|null
     */
    public function getName(): ?callable
    {
        return $this->name;
    }

    /**
     * 设置左侧树名称 Function
     * @param callable|null $name
     * @return $this
     */
    public function setName(?callable $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 获取左侧树父级ID Function
     * @return callable|null
     */
    public function getParentId(): ?callable
    {
        return $this->parentId;
    }

    /**
     * 设置左侧树父级ID Function
     * @param callable|null $parentId
     * @return $this
     */
    public function setParentId(?callable $parentId): self
    {
        $this->parentId = $parentId;
        return $this;
    }
}
