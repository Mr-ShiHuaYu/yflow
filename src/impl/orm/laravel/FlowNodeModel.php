<?php

namespace Yflow\impl\orm\laravel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Yflow\core\FlowEngine;
use Yflow\core\orm\dao\IFlowNodeDao;


class FlowNodeModel extends FlowBaseModel implements IFlowNodeDao
{
    private ?array $skipList = null;
    /**
     * 数据表主键
     */
    protected $primaryKey = 'id';

    /**
     * 模型名称
     */
    protected $table = 'flow_node';

    /**
     * 可批量赋值的属性
     */
    protected $fillable = [
        'node_type',
        'definition_id',
        'node_code',
        'node_name',
        'permission_flag',
        'node_ratio',
        'coordinate',
        'any_node_skip',
        'listener_type',
        'listener_path',
        'form_custom',
        'form_path',
        'version',
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
    protected $casts = [
        'id'            => 'string',
        'node_type'     => 'integer',
        'definition_id' => 'string',
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
     * 复制当前节点对象
     *
     * @return FlowNodeModel 新的节点实例
     */
    public function copy(): FlowNodeModel
    {
        return FlowEngine::newNode()
            ->setTenantId($this->getTenantId())
            ->setDelFlag($this->getDelFlag())
            ->setNodeType($this->getNodeType())
            ->setDefinitionId($this->getDefinitionId())
            ->setNodeCode($this->getNodeCode())
            ->setNodeName($this->getNodeName())
            ->setNodeRatio($this->getNodeRatio())
            ->setPermissionFlag($this->getPermissionFlag())
            ->setCoordinate($this->getCoordinate())
            ->setVersion($this->getVersion())
            ->setAnyNodeSkip($this->getAnyNodeSkip())
            ->setListenerType($this->getListenerType())
            ->setListenerPath($this->getListenerPath())
            ->setFormCustom($this->getFormCustom())
            ->setFormPath($this->getFormPath())
            ->setExt($this->getExt())
            ->setSkipList($this->getSkipList());
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
    public function setId(?int $id): self
    {
        $this->setAttribute('id', $id);
        return $this;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): ?string
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
    public function getUpdateTime(): ?string
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
     * 获取节点占比
     * @return string|null
     */
    public function getNodeRatio(): ?string
    {
        return $this->getAttribute('node_ratio');
    }

    /**
     * 设置节点占比
     * @param string|null $nodeRatio
     * @return $this
     */
    public function setNodeRatio(?string $nodeRatio): self
    {
        $this->setAttribute('node_ratio', $nodeRatio);
        return $this;
    }

    /**
     * 获取权限标识
     * @return string|null
     */
    public function getPermissionFlag(): ?string
    {
        return $this->getAttribute('permission_flag');
    }

    /**
     * 设置权限标识
     * @param string|null $permissionFlag
     * @return $this
     */
    public function setPermissionFlag(?string $permissionFlag): self
    {
        $this->setAttribute('permission_flag', $permissionFlag);
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

    /**
     * 获取任意节点跳转
     * @return string|null
     */
    public function getAnyNodeSkip(): ?string
    {
        return $this->getAttribute('any_node_skip');
    }

    /**
     * 设置任意节点跳转
     * @param string|null $anyNodeSkip
     * @return $this
     */
    public function setAnyNodeSkip(?string $anyNodeSkip): self
    {
        $this->setAttribute('any_node_skip', $anyNodeSkip);
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
     * 获取跳转列表
     * @return array|null
     */
    public function getSkipList(): ?array
    {
        return $this->skipList;
    }

    /**
     * 设置跳转列表
     * @param array|null $skipList
     * @return $this
     */
    public function setSkipList(?array $skipList): self
    {
        $this->skipList = $skipList;
        return $this;
    }

    public function getByNodeCodes(array $nodeCodes, int $definitionId): array
    {
        return $this->whereIn('node_code', $nodeCodes)
            ->where('definition_id', $definitionId)
            ->get()
            ->all();
    }

    public function deleteNodeByDefIds(array $defIds): int
    {
        return $this->whereIn('definition_id', $defIds)->delete();
    }
}
