<?php

namespace Yflow\impl\orm\laravel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Yflow\core\orm\dao\IFlowSkipDao;

/**
 * 节点跳转关联表模型
 */
class FlowSkipModel extends FlowBaseModel implements IFlowSkipDao
{
    private int $nodeId;
    /**
     * 数据表主键
     */
    protected $primaryKey = 'id';

    /**
     * 模型名称
     */
    protected $table = 'flow_skip';

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'definition_id',
        'now_node_code',
        'now_node_type',
        'next_node_code',
        'next_node_type',
        'skip_name',
        'skip_type',
        'skip_condition',
        'coordinate',
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
        'id'             => 'string',
        'definition_id'  => 'string',
        'now_node_type'  => 'integer',
        'next_node_type' => 'integer',
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

    // ==================== Java Entity 方法 ====================

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
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
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
     * 获取节点 ID
     * @return int|null
     */
    public function getNodeId(): ?int
    {
        return $this->nodeId;
    }

    /**
     * 设置节点 ID
     * @param int|null $nodeId
     * @return $this
     */
    public function setNodeId(?int $nodeId): self
    {
        $this->nodeId = $nodeId;
        return $this;
    }

    /**
     * 获取当前节点编码
     * @return string|null
     */
    public function getNowNodeCode(): ?string
    {
        return $this->getAttribute('now_node_code');
    }

    /**
     * 设置当前节点编码
     * @param string|null $nowNodeCode
     * @return $this
     */
    public function setNowNodeCode(?string $nowNodeCode): self
    {
        $this->setAttribute('now_node_code', $nowNodeCode);
        return $this;
    }

    /**
     * 获取当前节点类型
     * @return int|null
     */
    public function getNowNodeType(): ?int
    {
        return $this->getAttribute('now_node_type');
    }

    /**
     * 设置当前节点类型
     * @param int|null $nowNodeType
     * @return $this
     */
    public function setNowNodeType(?int $nowNodeType): self
    {
        $this->setAttribute('now_node_type', $nowNodeType);
        return $this;
    }

    /**
     * 获取下一个节点编码
     * @return string|null
     */
    public function getNextNodeCode(): ?string
    {
        return $this->getAttribute('next_node_code');
    }

    /**
     * 设置下一个节点编码
     * @param string|null $nextNodeCode
     * @return $this
     */
    public function setNextNodeCode(?string $nextNodeCode): self
    {
        $this->setAttribute('next_node_code', $nextNodeCode);
        return $this;
    }

    /**
     * 获取下一个节点类型
     * @return int|null
     */
    public function getNextNodeType(): ?int
    {
        return $this->getAttribute('next_node_type');
    }

    /**
     * 设置下一个节点类型
     * @param int|null $nextNodeType
     * @return $this
     */
    public function setNextNodeType(?int $nextNodeType): self
    {
        $this->setAttribute('next_node_type', $nextNodeType);
        return $this;
    }

    /**
     * 获取跳转名称
     * @return string|null
     */
    public function getSkipName(): ?string
    {
        return $this->getAttribute('skip_name');
    }

    /**
     * 设置跳转名称
     * @param string|null $skipName
     * @return $this
     */
    public function setSkipName(?string $skipName): self
    {
        $this->setAttribute('skip_name', $skipName);
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
     * 获取跳转条件
     * @return string|null
     */
    public function getSkipCondition(): ?string
    {
        return $this->getAttribute('skip_condition');
    }

    /**
     * 设置跳转条件
     * @param string|null $skipCondition
     * @return $this
     */
    public function setSkipCondition(?string $skipCondition): self
    {
        $this->setAttribute('skip_condition', $skipCondition);
        return $this;
    }

    /**
     * 获取坐标
     * @return string|null
     */
    public function getCoordinate(): ?string
    {
        return $this->getAttribute('coordinate');
    }

    /**
     * 设置坐标
     * @param string|null $coordinate
     * @return $this
     */
    public function setCoordinate(?string $coordinate): self
    {
        $this->setAttribute('coordinate', $coordinate);
        return $this;
    }

    public function deleteSkipByDefIds(array $defIds): int
    {
        return $this->whereIn('definition_id', $defIds)->delete();
    }
}
