<?php

namespace Yflow\ui\vo;

use JsonSerializable;

/**
 * 字典
 *
 *
 */
class Dict implements JsonSerializable
{
    /**
     * 字典标签
     * @var string|null
     */
    private ?string $label = null;

    /**
     * 字典值
     * @var string|null
     */
    private ?string $value = null;

    /**
     * 子字典列表
     * @var array<Dict>|null
     */
    private ?array $childList = null;

    /**
     * 获取字典标签
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * 设置字典标签
     * @param string|null $label
     * @return $this
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * 获取字典值
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * 设置字典值
     * @param string|null $value
     * @return $this
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 获取子字典列表
     * @return array<Dict>|null
     */
    public function getChildList(): ?array
    {
        return $this->childList;
    }

    /**
     * 设置子字典列表
     * @param array<Dict>|null $childList
     * @return $this
     */
    public function setChildList(?array $childList): self
    {
        $this->childList = $childList;
        return $this;
    }

    /**
     * 实现 JsonSerializable 接口
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'label'     => $this->label,
            'value'     => $this->value,
            'childList' => $this->childList,
        ];
    }
}
