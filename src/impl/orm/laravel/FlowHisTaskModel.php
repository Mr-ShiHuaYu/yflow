<?php

namespace Yflow\impl\orm\laravel;

use Yflow\core\orm\dao\IFlowHisTaskDao;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class FlowHisTaskModel extends FlowBaseModel implements IFlowHisTaskDao
{
    private string $flowName = '';
    private string $businessId = '';
    private array $permissionList;

    public function __construct()
    {
        parent::__construct();
        $this->permissionList = [];
    }

    /**
     * 数据表主键
     */
    protected $primaryKey = 'id';

    /**
     * 模型名称
     */
    protected $table = 'flow_his_task';

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'definition_id',
        'instance_id',
        'task_id',
        'node_code',
        'node_name',
        'node_type',
        'target_node_code',
        'target_node_name',
        'approver',
        'cooperate_type',
        'collaborator',
        'skip_type',
        'flow_status',
        'form_custom',
        'form_path',
        'message',
        'variable',
        'ext',
        'create_time',
        'update_time',
//        'create_by',
//        'update_by',
        'del_flag',
        'tenant_id',
    ];

    /**
     * 类型转换
     */
    protected $casts = [
        'id' => 'string',
        'definition_id' => 'string',
        'instance_id' => 'string',
        'task_id' => 'string',
        'node_type' => 'integer',
        'cooperate_type' => 'integer',
    ];

    /**
     * 是否自动维护时间戳
     */
    public $timestamps = false;



    /**
     * 关联流程定义
     */
    public function definition(): BelongsTo
    {
        return $this->belongsTo(FlowDefinitionModel::class, 'definition_id', 'id');
    }

    /**
     * 关联流程实例
     */
    public function instance(): BelongsTo
    {
        return $this->belongsTo(FlowInstanceModel::class, 'instance_id', 'id');
    }

    /**
     * 关联待办任务
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(FlowTaskModel::class, 'task_id', 'id');
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
     * @param string|null $createTime
     * @return $this
     */
    public function setCreateTime(?string $createTime): self
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
     * 获取流程实例 ID
     * @return int|null
     */
    public function getInstanceId(): ?int
    {
        return $this->getAttribute('instance_id');
    }

    /**
     * 设置流程实例 ID
     * @param int|null $instanceId
     * @return $this
     */
    public function setInstanceId(?int $instanceId): self
    {
        $this->setAttribute('instance_id', $instanceId);
        return $this;
    }

    /**
     * 获取协作类型
     * @return int|null
     */
    public function getCooperateType(): ?int
    {
        return $this->getAttribute('cooperate_type');
    }

    /**
     * 设置协作类型
     * @param int|null $cooperateType
     * @return $this
     */
    public function setCooperateType(?int $cooperateType): self
    {
        $this->setAttribute('cooperate_type', $cooperateType);
        return $this;
    }

    /**
     * 获取任务 ID
     * @return int|null
     */
    public function getTaskId(): ?int
    {
        return $this->getAttribute('task_id');
    }

    /**
     * 设置任务 ID
     * @param int|null $taskId
     * @return $this
     */
    public function setTaskId(?int $taskId): self
    {
        $this->setAttribute('task_id', $taskId);
        return $this;
    }

    /**
     * 获取业务 ID
     * @return string|null
     */
    public function getBusinessId(): ?string
    {
        return $this->businessId;
    }

    /**
     * 设置业务 ID
     * @param string|null $businessId
     * @return $this
     */
    public function setBusinessId(?string $businessId): self
    {
        $this->businessId = $businessId;
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
     * 获取目标节点编码
     * @return string|null
     */
    public function getTargetNodeCode(): ?string
    {
        return $this->getAttribute('target_node_code');
    }

    /**
     * 设置目标节点编码
     * @param string|null $targetNodeCode
     * @return $this
     */
    public function setTargetNodeCode(?string $targetNodeCode): self
    {
        $this->setAttribute('target_node_code', $targetNodeCode);
        return $this;
    }

    /**
     * 获取目标节点名称
     * @return string|null
     */
    public function getTargetNodeName(): ?string
    {
        return $this->getAttribute('target_node_name');
    }

    /**
     * 设置目标节点名称
     * @param string|null $targetNodeName
     * @return $this
     */
    public function setTargetNodeName(?string $targetNodeName): self
    {
        $this->setAttribute('target_node_name', $targetNodeName);
        return $this;
    }

    /**
     * 获取审批人
     * @return string|null
     */
    public function getApprover(): ?string
    {
        return $this->getAttribute('approver');
    }

    /**
     * 设置审批人
     * @param string|null $approver
     * @return $this
     */
    public function setApprover(?string $approver): self
    {
        $this->setAttribute('approver', $approver);
        return $this;
    }

    /**
     * 获取协办人
     * @return string|null
     */
    public function getCollaborator(): ?string
    {
        return $this->getAttribute('collaborator');
    }

    /**
     * 设置协办人
     * @param string|null $collaborator
     * @return $this
     */
    public function setCollaborator(?string $collaborator): self
    {
        $this->setAttribute('collaborator', $collaborator);
        return $this;
    }

    /**
     * 获取权限列表
     * @return array|null
     */
    public function getPermissionList(): ?array
    {
        return $this->permissionList;
    }

    /**
     * 设置权限列表
     * @param array|null $permissionList
     * @return $this
     */
    public function setPermissionList(?array $permissionList): self
    {
        $this->permissionList = $permissionList;
        return $this;
    }

    /**
     * 获取跳转类型
     * @return string|null
     */
    public function getSkipType(): ?string
    {
        return $this->getAttribute('skip_type');
    }

    /**
     * 设置跳转类型
     * @param string|null $skipType
     * @return $this
     */
    public function setSkipType(?string $skipType): self
    {
        $this->setAttribute('skip_type', $skipType);
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
     * 获取消息
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->getAttribute('message');
    }

    /**
     * 设置消息
     * @param string|null $message
     * @return $this
     */
    public function setMessage(?string $message): self
    {
        $this->setAttribute('message', $message);
        return $this;
    }

    /**
     * 获取变量
     * @return string|null
     */
    public function getVariable(): ?string
    {
        return $this->getAttribute('variable');
    }

    /**
     * 设置变量
     * @param string|null $variable
     * @return $this
     */
    public function setVariable(?string $variable): self
    {
        $this->setAttribute('variable', $variable === 'null' ? null : $variable);
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
        $this->setAttribute('ext', $ext === 'null' ? null : $ext);
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


    public function getNoReject(int $instanceId): array
    {
        return $this->where('instance_id', $instanceId)
            ->where('flow_status', '!=', 'reject')
            ->get()
            ->all();
    }

    public function getByInsAndNodeCodes(int $instanceId, array $nodeCodes): array
    {
        return $this->where('instance_id', $instanceId)
            ->whereIn('node_code', $nodeCodes)
            ->orderByDesc('create_time')
            ->get()
            ->all();
    }

    public function deleteByInsIds(array $instanceIds): int
    {
        return $this->whereIn('instance_id', $instanceIds)->delete();
    }

    public function listByTaskIdAndCooperateTypes(int $taskId, array $cooperateTypes): array
    {
        return $this->where('task_id', $taskId)
            ->whereIn('cooperate_type', $cooperateTypes)
            ->get()
            ->all();
    }
}
