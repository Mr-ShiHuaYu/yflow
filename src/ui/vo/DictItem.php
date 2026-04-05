<?php

namespace Yflow\ui\vo;

use JsonSerializable;

/**
 * 字典项
 */
class DictItem implements JsonSerializable
{
    /**
     * 字典标签
     * @var string|null
     */
    private ?string $label;

    /**
     * 字典值
     * @var string|null
     */
    private ?string $value;

    /**
     * 是否选中
     * @var bool
     */
    private bool $selected;

    /**
     * 构造函数
     * @param string|null $label
     * @param string|null $value
     * @param bool $selected
     */
    public function __construct(?string $label = null, ?string $value = null, bool $selected = false)
    {
        $this->label = $label;
        $this->value = $value;
        $this->selected = $selected;
    }

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
     * 是否选中
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * 设置是否选中
     * @param bool $selected
     * @return $this
     */
    public function setSelected(bool $selected): self
    {
        $this->selected = $selected;
        return $this;
    }

    /**
     * 实现 JsonSerializable 接口
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'selected' => $this->selected,
        ];
    }
}
