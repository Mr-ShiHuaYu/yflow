<?php

namespace Yflow\ui\vo;


use JsonSerializable;

/**
 * 子节点
 */
class ChildNode implements JsonSerializable
{
    /**
     * 子节点编码
     * @var string|null
     */
    private ?string $code = null;

    /**
     * 子节点描述
     * @var string|null
     */
    private ?string $desc = null;

    /**
     * 子节点标签
     * @var string|null
     */
    private ?string $label = null;

    /**
     * 子节点类型
     * @var int
     */
    private int $type = 0;

    /**
     * 是否必填
     * @var bool
     */
    private bool $must = false;

    /**
     * 是否多选
     * @var bool
     */
    private bool $multiple = false;

    /**
     * 字典项列表
     * @var array<DictItem>|null
     */
    private ?array $dict = null;

    /**
     * 获取子节点编码
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * 设置子节点编码
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 获取子节点描述
     * @return string|null
     */
    public function getDesc(): ?string
    {
        return $this->desc;
    }

    /**
     * 设置子节点描述
     * @param string|null $desc
     * @return $this
     */
    public function setDesc(?string $desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * 获取子节点标签
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * 设置子节点标签
     * @param string|null $label
     * @return $this
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * 获取子节点类型
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置子节点类型
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 是否必填
     * @return bool
     */
    public function isMust(): bool
    {
        return $this->must;
    }

    /**
     * 设置是否必填
     * @param bool $must
     * @return $this
     */
    public function setMust(bool $must): self
    {
        $this->must = $must;
        return $this;
    }

    /**
     * 是否多选
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * 设置是否多选
     * @param bool $multiple
     * @return $this
     */
    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * 获取字典项列表
     * @return array<DictItem>|null
     */
    public function getDict(): ?array
    {
        return $this->dict;
    }

    /**
     * 设置字典项列表
     * @param array<DictItem>|null $dict
     * @return $this
     */
    public function setDict(?array $dict): self
    {
        $this->dict = $dict;
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
            'desc' => $this->desc,
            'label' => $this->label,
            'type' => $this->type,
            'must' => $this->must,
            'multiple' => $this->multiple,
            'dict' => $this->dict,
        ];
    }
}
