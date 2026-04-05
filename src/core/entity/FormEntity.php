<?php

namespace Yflow\core\entity;


/**
 * @author vanlin
 * @since 2024/8/19 10:30
 */
class FormEntity
{
    /**
     * 主键
     * @var int
     */
    public int $id;

    /**
     * 表单编码
     * @var string
     */
    public string $formCode;

    /**
     * 表单名称
     * @var string
     */
    public string $formName;

    /**
     * 表单版本
     * @var string
     */
    public string $version;

    /**
     * 是否发布（0未发布 1已发布 9失效）
     * @var int
     */
    public int $isPublish;

    /**
     * 表单类型（0内置表单 存 form_content        1外挂表单 存form_path）
     * @var int
     */
    public int $formType;

    /**
     * 表单路径
     * @var string
     */
    public string $formPath;

    /**
     * 表单内容
     * @var string
     */
    public string $formContent;

    /**
     * 表单扩展，用户自行使用
     * @var string
     */
    public string $ext;

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
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormCode(): string
    {
        return $this->formCode;
    }

    /**
     * @param string $formCode
     * @return $this
     */
    public function setFormCode(string $formCode): static
    {
        $this->formCode = $formCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormName(): string
    {
        return $this->formName;
    }

    /**
     * @param string $formName
     * @return $this
     */
    public function setFormName(string $formName): static
    {
        $this->formName = $formName;
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
    public function setVersion(string $version): static
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsPublish(): int
    {
        return $this->isPublish;
    }

    /**
     * @param int $isPublish
     * @return $this
     */
    public function setIsPublish(int $isPublish): static
    {
        $this->isPublish = $isPublish;
        return $this;
    }

    /**
     * @return int
     */
    public function getFormType(): int
    {
        return $this->formType;
    }

    /**
     * @param int $formType
     * @return $this
     */
    public function setFormType(int $formType): static
    {
        $this->formType = $formType;
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
    public function setFormPath(string $formPath): static
    {
        $this->formPath = $formPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormContent(): string
    {
        return $this->formContent;
    }

    /**
     * @param string $formContent
     * @return $this
     */
    public function setFormContent(string $formContent): static
    {
        $this->formContent = $formContent;
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
    public function setExt(string $ext): static
    {
        $this->ext = $ext;
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
    public function setCreateTime(string $createTime): static
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
    public function setUpdateTime(string $updateTime): static
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
    public function setCreateBy(string $createBy): static
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
    public function setUpdateBy(string $updateBy): static
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
    public function setTenantId(string $tenantId): static
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
    public function setDelFlag(string $delFlag): static
    {
        $this->delFlag = $delFlag;
        return $this;
    }
}
