<?php

namespace Yflow\impl\orm\laravel;

use Yflow\core\orm\dao\IFlowFormDao;

class FlowFormModel extends FlowBaseModel implements IFlowFormDao
{

    /**
     * 数据表主键
     */
    protected $primaryKey = 'id';

    /**
     * 模型名称
     */
    protected $table = 'flow_form';

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'form_code',
        'form_name',
        'version',
        'is_publish',
        'form_type',
        'form_path',
        'form_content',
        'ext',
        'create_time',
        'update_time',
        'del_flag',
        'tenant_id',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'id'         => 'string',
        'is_publish' => 'integer',
    ];

    /**
     * 是否自动维护时间戳
     */
    public $timestamps = false;


    public function queryByCodeList(array $formCodeList): array
    {
        return $this->whereIn('form_code', $formCodeList)->get()->all();
    }

    /**
     * 获取 ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * 设置 ID
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->setAttribute('id', $id);
        return $this;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime()
    {
        return $this->getAttribute('create_time');
    }

    /**
     * 设置创建时间
     * @param string $createTime
     * @return $this
     */
    public function setCreateTime(string $createTime): self
    {
        $this->setAttribute('create_time', $createTime);
        return $this;
    }

    /**
     * 获取更新时间
     */
    public function getUpdateTime()
    {
        return $this->getAttribute('update_time');
    }

    /**
     * 设置更新时间
     * @param string $updateTime
     * @return $this
     */
    public function setUpdateTime(string $updateTime): self
    {
        $this->setAttribute('update_time', $updateTime);
        return $this;
    }
//
//    /**
//     * 获取创建人
//     * @return string|null
//     */
//    public function getCreateBy(): ?string
//    {
//        return $this->getAttribute('create_by');
//    }
//
//    /**
//     * 设置创建人
//     * @param string|null $createBy
//     * @return $this
//     */
//    public function setCreateBy(?string $createBy): self
//    {
//        $this->setAttribute('create_by', $createBy);
//        return $this;
//    }
//
//    /**
//     * 获取更新人
//     * @return string|null
//     */
//    public function getUpdateBy(): ?string
//    {
//        return $this->getAttribute('update_by');
//    }
//
//    /**
//     * 设置更新人
//     * @param string|null $updateBy
//     * @return $this
//     */
//    public function setUpdateBy(?string $updateBy): self
//    {
//        $this->setAttribute('update_by', $updateBy);
//        return $this;
//    }

    /**
     * 获取租户 ID
     * @return string|null
     */
    public function getTenantId(): ?string
    {
        return $this->getAttribute('tenant_id');
    }

    /**
     * 设置租户 ID
     * @param string|null $tenantId
     * @return $this
     */
    public function setTenantId(?string $tenantId): self
    {
        $this->setAttribute('tenant_id', $tenantId);
        return $this;
    }

    /**
     * 获取删除标志
     * @return string|null
     */
    public function getDelFlag(): ?string
    {
        return $this->getAttribute('del_flag');
    }

    /**
     * 设置删除标志
     * @param string|null $delFlag
     * @return $this
     */
    public function setDelFlag(?string $delFlag): self
    {
        $this->setAttribute('del_flag', $delFlag);
        return $this;
    }

    /**
     * 获取表单编码
     * @return string|null
     */
    public function getFormCode(): ?string
    {
        return $this->getAttribute('form_code');
    }

    /**
     * 设置表单编码
     * @param string|null $formCode
     * @return $this
     */
    public function setFormCode(?string $formCode): self
    {
        $this->setAttribute('form_code', $formCode);
        return $this;
    }

    /**
     * 获取表单名称
     * @return string|null
     */
    public function getFormName(): ?string
    {
        return $this->getAttribute('form_name');
    }

    /**
     * 设置表单名称
     * @param string|null $formName
     * @return $this
     */
    public function setFormName(?string $formName): self
    {
        $this->setAttribute('form_name', $formName);
        return $this;
    }

    /**
     * 获取版本号
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->getAttribute('version');
    }

    /**
     * 设置版本号
     * @param string|null $version
     * @return $this
     */
    public function setVersion(?string $version): self
    {
        $this->setAttribute('version', $version);
        return $this;
    }

    /**
     * 获取是否发布
     * @return int|null
     */
    public function getIsPublish(): ?int
    {
        return $this->getAttribute('is_publish');
    }

    /**
     * 设置是否发布
     * @param int|null $isPublish
     * @return $this
     */
    public function setIsPublish(?int $isPublish): self
    {
        $this->setAttribute('is_publish', $isPublish);
        return $this;
    }

    /**
     * 获取表单类型
     * @return int|null
     */
    public function getFormType(): ?int
    {
        return $this->getAttribute('form_type');
    }

    /**
     * 设置表单类型
     * @param int|null $formType
     * @return $this
     */
    public function setFormType(?int $formType): self
    {
        $this->setAttribute('form_type', $formType);
        return $this;
    }

    /**
     * 获取表单内容
     * @return string|null
     */
    public function getFormContent(): ?string
    {
        return $this->getAttribute('form_content');
    }

    /**
     * 设置表单内容
     * @param string|null $formContent
     * @return $this
     */
    public function setFormContent(?string $formContent): self
    {
        $this->setAttribute('form_content', $formContent);
        return $this;
    }

    /**
     * 获取表单路径
     * @return string|null
     */
    public function getFormPath(): ?string
    {
        return $this->getAttribute('form_path');
    }

    /**
     * 设置表单路径
     * @param string|null $formPath
     * @return $this
     */
    public function setFormPath(?string $formPath): self
    {
        $this->setAttribute('form_path', $formPath);
        return $this;
    }

    /**
     * 获取扩展字段
     * @return string|null
     */
    public function getExt(): ?string
    {
        return $this->getAttribute('ext');
    }

    /**
     * 设置扩展字段
     * @param string|null $ext
     * @return $this
     */
    public function setExt(?string $ext): self
    {
        $this->setAttribute('ext', $ext);
        return $this;
    }
}
