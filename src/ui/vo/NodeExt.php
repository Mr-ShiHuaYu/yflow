<?php

namespace Yflow\ui\vo;

use JsonSerializable;

/**
 * 节点扩展属性
 *
 *
 * @since 2025/2/18
 */
class NodeExt implements JsonSerializable
{
    /**
     * 扩展属性编码
     * @var string|null
     */
    private ?string $code = null;

    /**
     * 扩展属性名称
     * @var string|null
     */
    private ?string $name = null;

    /**
     * 扩展属性描述
     * @var string|null
     */
    private ?string $desc = null;

    /**
     * 扩展属性类型
     * @var int
     */
    private int $type = 0;

    /**
     * 子节点列表
     * @var array<ChildNode>|null
     */
    private ?array $childs = null;

    /**
     * 获取扩展属性编码
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * 设置扩展属性编码
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 获取扩展属性名称
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * 设置扩展属性名称
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 获取扩展属性描述
     * @return string|null
     */
    public function getDesc(): ?string
    {
        return $this->desc;
    }

    /**
     * 设置扩展属性描述
     * @param string|null $desc
     * @return $this
     */
    public function setDesc(?string $desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * 获取扩展属性类型
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置扩展属性类型
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 获取子节点列表
     * @return array<ChildNode>|null
     */
    public function getChilds(): ?array
    {
        return $this->childs;
    }

    /**
     * 设置子节点列表
     * @param array<ChildNode>|null $childs
     * @return $this
     */
    public function setChilds(?array $childs): self
    {
        $this->childs = $childs;
        return $this;
    }

    /**
     * 实现 JsonSerializable 接口
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'desc' => $this->desc,
            'type' => $this->type,
            'childs' => $this->childs,
        ];
    }
}
