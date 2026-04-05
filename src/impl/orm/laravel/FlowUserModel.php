<?php

namespace Yflow\impl\orm\laravel;

use Yflow\core\orm\dao\IFlowUserDao;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 流程用户表模型
 */
class FlowUserModel extends FlowBaseModel implements IFlowUserDao
{
    /**
     * 数据表主键
     */
    protected $primaryKey = 'id';

    /**
     * 模型名称
     */
    protected $table = 'flow_user';

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'type',
        'processed_by',
        'associated',
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
        'type' => 'string',
        'associated' => 'string',
    ];

    /**
     * 是否自动维护时间戳
     */
    public $timestamps = false;

    /**
     * 关联待办任务
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(FlowTaskModel::class, 'associated', 'id');
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
     * @param int|null $id
     * @return $this
     */
    public function setId(int $id = null): self
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
    public function setCreateTime(string $createTime = null): self
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
     * 获取人员类型
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->getAttribute('type');
    }

    /**
     * 设置人员类型
     * @param string|null $type
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->setAttribute('type', $type);
        return $this;
    }

    /**
     * 获取权限人
     * @return string|null
     */
    public function getProcessedBy(): ?string
    {
        return $this->getAttribute('processed_by');
    }

    /**
     * 设置权限人
     * @param string|null $processedBy
     * @return $this
     */
    public function setProcessedBy(?string $processedBy): self
    {
        $this->setAttribute('processed_by', $processedBy);
        return $this;
    }

    /**
     * 获取任务表 ID
     * @return int|null
     */
    public function getAssociated(): ?int
    {
        return $this->getAttribute('associated');
    }

    /**
     * 设置任务表 ID
     * @param int|null $associated
     * @return $this
     */
    public function setAssociated(?int $associated): self
    {
        $this->setAttribute('associated', $associated);
        return $this;
    }

    public function deleteByTaskIds(array $taskIdList): int
    {
        return $this->whereIn('associated', $taskIdList)->delete();
    }

    public function listByAssociatedAndTypes(array $associatedList, array $types): array
    {
        $builder = $this->query();

        if (!empty($associatedList)) {
            if (count($associatedList) == 1) {
                $builder->where('associated', $associatedList[0]);
            } else {
                $builder->whereIn('associated', $associatedList);
            }
        }

        if (!empty($types)) {
            $builder->whereIn('type', $types);
        }

        return $builder->get()->all();
    }

    public function listByProcessedBys(?int $associated, array $processedBys, array $types): array
    {
        $builder = $this->query();

        if ($associated !== null) {
            $builder->where('associated', $associated);
        }

        if (!empty($processedBys)) {
            if (count($processedBys) == 1) {
                $builder->where('processed_by', $processedBys[0]);
            } else {
                $builder->whereIn('processed_by', $processedBys);
            }
        }

        if (!empty($types)) {
            $builder->whereIn('type', $types);
        }

        return $builder->get()->all();
    }
}
