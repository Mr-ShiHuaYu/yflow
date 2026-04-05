<?php

namespace Yflow\core\dto;


/**
 * InfoItem - 提示信息项
 */
class InfoItem
{

    /**
     * 前缀
     */
    public ?string $prefix = null;

    /**
     * 前缀样式
     */
    public ?array $prefixStyle = null;

    /**
     * 内容
     */
    public ?string $content = null;

    /**
     * 内容样式
     */
    public ?array $contentStyle = null;

    /**
     * 行样式
     */
    public ?array $rowStyle = null;

    /**
     * 构造函数
     *
     * @param string|null $prefix
     * @param array|null $prefixStyle
     * @param string|null $content
     * @param array|null $contentStyle
     * @param array|null $rowStyle
     */
    public function __construct(
        ?string $prefix = null,
        ?array  $prefixStyle = null,
        ?string $content = null,
        ?array  $contentStyle = null,
        ?array  $rowStyle = null
    )
    {
        $this->prefix       = $prefix;
        $this->prefixStyle  = $prefixStyle;
        $this->content      = $content;
        $this->contentStyle = $contentStyle;
        $this->rowStyle     = $rowStyle;
    }

    /**
     * 获取前缀
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * 设置前缀
     * @param string|null $prefix
     * @return self
     */
    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * 获取前缀样式
     * @return array|null
     */
    public function getPrefixStyle(): ?array
    {
        return $this->prefixStyle;
    }

    /**
     * 设置前缀样式
     * @param array|null $prefixStyle
     * @return self
     */
    public function setPrefixStyle(?array $prefixStyle): self
    {
        $this->prefixStyle = $prefixStyle;
        return $this;
    }

    /**
     * 获取内容
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * 设置内容
     * @param string|null $content
     * @return self
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 获取内容样式
     * @return array|null
     */
    public function getContentStyle(): ?array
    {
        return $this->contentStyle;
    }

    /**
     * 设置内容样式
     * @param array|null $contentStyle
     * @return self
     */
    public function setContentStyle(?array $contentStyle): self
    {
        $this->contentStyle = $contentStyle;
        return $this;
    }

    /**
     * 获取行样式
     * @return array|null
     */
    public function getRowStyle(): ?array
    {
        return $this->rowStyle;
    }

    /**
     * 设置行样式
     * @param array|null $rowStyle
     * @return self
     */
    public function setRowStyle(?array $rowStyle): self
    {
        $this->rowStyle = $rowStyle;
        return $this;
    }
}
