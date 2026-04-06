<?php

namespace Yflow\impl\orm\laravel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Yflow\core\FlowEngine;
use Yflow\core\orm\dao\IFlowInstanceDao;

class FlowInstanceModel extends FlowBaseModel implements IFlowInstanceDao
{
    /**
     * 是否自动维护时间戳
     */
    public $timestamps = false;
    /**
     * 数据表主键
     */
    protected $primaryKey = 'id';
    /**
     * 模型名称
     */
    protected $table = 'flow_instance';
    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'definition_id',
        'business_id',
        'node_type',
        'node_code',
        'node_name',
        'variable',
        'flow_status',
        'activity_status',
        'def_json',
        'create_time',
        'create_by',
        'update_time',
        'update_by',
        'ext',
        'del_flag',
        'tenant_id',
    ];
    /**
     * 类型转换
     */
    protected      $casts      = [
        'id'              => 'string',
        'definition_id'   => 'string',
        'node_type'       => 'integer',
        'activity_status' => 'integer',
    ];
    private string $flowName   = '';
    private string $formCustom = '';
    private string $formPath   = '';

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
     * 获取流程定义 ID
     * @return int|null
     */
    public function getDefinitionId(): ?int
    {
        return $this->getAttribute('definition_id');
    }

    /**
     * 设置流程定义 ID
     * @param int|null $definitionId
     * @return $this
     */
    public function setDefinitionId(?int $definitionId): self
    {
        $this->setAttribute('definition_id', $definitionId);
        return $this;
    }

    /**
     * 获取流程名称
     * @return string|null
     */
    public function getFlowName(): ?string
    {
        return $this->flowName;
    }

    /**
     * 设置流程名称
     * @param string|null $flowName
     * @return $this
     */
    public function setFlowName(?string $flowName): self
    {
        $this->flowName = $flowName;
        return $this;
    }

    /**
     * 获取业务 ID
     * @return string|null
     */
    public function getBusinessId(): ?string
    {
        return $this->getAttribute('business_id');
    }

    /**
     * 设置业务 ID
     * @param string|null $businessId
     * @return $this
     */
    public function setBusinessId(?string $businessId): self
    {
        $this->setAttribute('business_id', $businessId);
        return $this;
    }

    /**
     * 获取节点类型
     * @return int|null
     */
    public function getNodeType(): ?int
    {
        return $this->getAttribute('node_type');
    }

    /**
     * 设置节点类型
     * @param int|null $nodeType
     * @return $this
     */
    public function setNodeType(?int $nodeType): self
    {
        $this->setAttribute('node_type', $nodeType);
        return $this;
    }

    /**
     * 获取节点编码
     * @return string|null
     */
    public function getNodeCode(): ?string
    {
        return $this->getAttribute('node_code');
    }

    /**
     * 设置节点编码
     * @param string|null $nodeCode
     * @return $this
     */
    public function setNodeCode(?string $nodeCode): self
    {
        $this->setAttribute('node_code', $nodeCode);
        return $this;
    }

    /**
     * 获取节点名称
     * @return string|null
     */
    public function getNodeName(): ?string
    {
        return $this->getAttribute('node_name');
    }

    /**
     * 设置节点名称
     * @param string|null $nodeName
     * @return $this
     */
    public function setNodeName(?string $nodeName): self
    {
        $this->setAttribute('node_name', $nodeName);
        return $this;
    }

    /**
     * 获取流程变量
     * @return ?string
     */
    public function getVariable(): ?string
    {
        return $this->getAttribute('variable');
    }

    public function getVariableMap(): array
    {
        return FlowEngine::$jsonConvert->strToMap($this->getVariable());
    }

    /**
     * 设置流程变量
     * @param string|null $variable
     * @return $this
     */
    public function setVariable(?string $variable): self
    {
        $this->setAttribute('variable', $variable);
        return $this;
    }

    /**
     * 获取流程状态
     * @return string|null
     */
    public function getFlowStatus(): ?string
    {
        return $this->getAttribute('flow_status');
    }

    /**
     * 设置流程状态
     * @param string|null $flowStatus
     * @return $this
     */
    public function setFlowStatus(?string $flowStatus): self
    {
        $this->setAttribute('flow_status', $flowStatus);
        return $this;
    }

    /**
     * 获取表单是否自定义
     * @return string|null
     */
    public function getFormCustom(): ?string
    {
        return $this->formCustom;
    }

    /**
     * 设置表单是否自定义
     * @param string|null $formCustom
     * @return $this
     */
    public function setFormCustom(?string $formCustom): self
    {
        $this->formCustom = $formCustom;
        return $this;
    }

    /**
     * 获取表单路径
     * @return string|null
     */
    public function getFormPath(): ?string
    {
        return $this->formPath;
    }

    /**
     * 设置表单路径
     * @param string|null $formPath
     * @return $this
     */
    public function setFormPath(?string $formPath): self
    {
        $this->formPath = $formPath;
        return $this;
    }

    /**
     * 获取定义 JSON
     * @return string|null
     */
    public function getDefJson(): ?string
    {
        return $this->getAttribute('def_json');
    }

    /**
     * 设置定义 JSON
     * @param string|null $defJson
     * @return $this
     */
    public function setDefJson(?string $defJson): self
    {
        $this->setAttribute('def_json', $defJson);
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
     * 关联流程定义
     */
    public function definition(): BelongsTo
    {
        return $this->belongsTo(FlowDefinitionModel::class, 'definition_id', 'id');
    }

    /**
     * 关联待办任务
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(FlowTaskModel::class, 'instance_id', 'id');
    }

    /**
     * 关联历史任务
     */
    public function hisTasks(): HasMany
    {
        return $this->hasMany(FlowHisTaskModel::class, 'instance_id', 'id');
    }

    /**
     * 根据流程定义 ID 批量查询
     * @param array $defIds 流程定义 ID 集合
     * @return array 实体列表
     */
    public function getByDefIds(array $defIds): array
    {
        return $this->whereIn('definition_id', $defIds)->get()->all();
    }
}
