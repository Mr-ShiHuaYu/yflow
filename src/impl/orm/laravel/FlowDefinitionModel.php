<?php

namespace Yflow\impl\orm\laravel;

use Yflow\core\orm\dao\IFlowDefinitionDao;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlowDefinitionModel extends FlowBaseModel implements IFlowDefinitionDao
{
    /**
     * @var FlowNodeModel[]
     */
    public array $nodeList = [];

    /**
     * @var FlowUserModel[]
     */
    public array $userList = [];
    /**
     * 数据表主键
     */
    protected $primaryKey = 'id';

    /**
     * 模型名称
     */
    protected $table = 'flow_definition';

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'flow_code',
        'flow_name',
        'model_value',
        'category',
        'version',
        'is_publish',
        'form_custom',
        'form_path',
        'activity_status',
        'listener_type',
        'listener_path',
        'ext',
        'create_time',
        'create_by',
        'update_time',
        'update_by',
        'del_flag',
        'tenant_id',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'id' => 'string',
        'is_publish' => 'integer',
        'activity_status' => 'integer',
    ];

    /**
     * 是否自动维护时间戳
     */
    public $timestamps = false;

    /**
     * 关联流程节点
     */
    public function nodes(): HasMany
    {
        return $this->hasMany(FlowNodeModel::class, 'definition_id', 'id');
    }

    /**
     * 关联流程跳转
     */
    public function skips(): HasMany
    {
        return $this->hasMany(FlowSkipModel::class, 'definition_id', 'id');
    }

    /**
     * 关联流程实例
     */
    public function instances(): HasMany
    {
        return $this->hasMany(FlowInstanceModel::class, 'definition_id', 'id');
    }

    /**
     * 关联待办任务
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(FlowTaskModel::class, 'definition_id', 'id');
    }

    /**
     * 关联历史任务
     */
    public function hisTasks(): HasMany
    {
        return $this->hasMany(FlowHisTaskModel::class, 'definition_id', 'id');
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

    /**
     * 获取创建人
     * @return string|null
     */
    public function getCreateBy(): ?string
    {
        return $this->getAttribute('create_by');
    }

    /**
     * 设置创建人
     * @param string|null $createBy
     * @return $this
     */
    public function setCreateBy(?string $createBy): self
    {
        $this->setAttribute('create_by', $createBy);
        return $this;
    }

    /**
     * 获取更新人
     * @return string|null
     */
    public function getUpdateBy(): ?string
    {
        return $this->getAttribute('update_by');
    }

    /**
     * 设置更新人
     * @param string|null $updateBy
     * @return $this
     */
    public function setUpdateBy(?string $updateBy): self
    {
        $this->setAttribute('update_by', $updateBy);
        return $this;
    }

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
     * 获取流程编码
     * @return string|null
     */
    public function getFlowCode(): ?string
    {
        return $this->getAttribute('flow_code');
    }

    /**
     * 设置流程编码
     * @param string|null $flowCode
     * @return $this
     */
    public function setFlowCode(?string $flowCode): self
    {
        $this->setAttribute('flow_code', $flowCode);
        return $this;
    }

    /**
     * 获取流程名称
     * @return string|null
     */
    public function getFlowName(): ?string
    {
        return $this->getAttribute('flow_name');
    }

    /**
     * 设置流程名称
     * @param string|null $flowName
     * @return $this
     */
    public function setFlowName(?string $flowName): self
    {
        $this->setAttribute('flow_name', $flowName);
        return $this;
    }

    /**
     * 获取设计器模型
     * @return string|null
     */
    public function getModelValue(): ?string
    {
        return $this->getAttribute('model_value');
    }

    /**
     * 设置设计器模型
     * @param string|null $modelValue
     * @return $this
     */
    public function setModelValue(?string $modelValue): self
    {
        $this->setAttribute('model_value', $modelValue);
        return $this;
    }

    /**
     * 获取分类
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->getAttribute('category');
    }

    /**
     * 设置分类
     * @param string|null $category
     * @return $this
     */
    public function setCategory(?string $category): self
    {
        $this->setAttribute('category', $category);
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
     * 获取表单是否自定义
     * @return string|null
     */
    public function getFormCustom(): ?string
    {
        return $this->getAttribute('form_custom');
    }

    /**
     * 设置表单是否自定义
     * @param string|null $formCustom
     * @return $this
     */
    public function setFormCustom(?string $formCustom): self
    {
        $this->setAttribute('form_custom', $formCustom);
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
     * @return mixed
     */
    public function getExt(): mixed
    {
        return $this->getAttribute('ext');
    }

    /**
     * 设置扩展字段
     * @param mixed $ext
     * @return $this
     */
    public function setExt(mixed $ext): self
    {
        $this->setAttribute('ext', $ext);
        return $this;
    }

    /**
     * 获取节点列表
     * @return array|null
     */
    public function getNodeList(): array|null
    {
        return $this->nodeList;
    }

    /**
     * 设置节点列表
     * @param array|null $nodeList
     * @return $this
     */
    public function setNodeList(array|null $nodeList): self
    {
        $this->nodeList = $nodeList;
        return $this;
    }

    /**
     * 获取用户列表
     * @return array|null
     */
    public function getUserList(): array|null
    {
        return $this->userList;
    }

    /**
     * 设置用户列表
     * @param array|null $userList
     * @return $this
     */
    public function setUserList(?array $userList): self
    {
        $this->userList = $userList;
        return $this;
    }

    /**
     * 获取激活状态
     * @return int|null
     */
    public function getActivityStatus(): ?int
    {
        return $this->getAttribute('activity_status');
    }

    /**
     * 设置激活状态
     * @param int|null $activityStatus
     * @return $this
     */
    public function setActivityStatus(?int $activityStatus): self
    {
        $this->setAttribute('activity_status', $activityStatus);
        return $this;
    }

    /**
     * 获取监听器类型
     * @return string|null
     */
    public function getListenerType(): ?string
    {
        return $this->getAttribute('listener_type');
    }

    /**
     * 设置监听器类型
     * @param string|null $listenerType
     * @return $this
     */
    public function setListenerType(?string $listenerType): self
    {
        $this->setAttribute('listener_type', $listenerType);
        return $this;
    }

    /**
     * 获取监听器路径
     * @return string|null
     */
    public function getListenerPath(): ?string
    {
        return $this->getAttribute('listener_path');
    }

    /**
     * 设置监听器路径
     * @param string|null $listenerPath
     * @return $this
     */
    public function setListenerPath(?string $listenerPath): self
    {
        $this->setAttribute('listener_path', $listenerPath);
        return $this;
    }


    public function queryByCodeList(array $flowCodeList): array
    {
        return $this->whereIn('flow_code', $flowCodeList)->get()->all();
    }

    public function updatePublishStatus(array $ids, int $publishStatus): void
    {
        $this->whereIn('id', $ids)->update(['is_publish' => $publishStatus]);
    }
}
