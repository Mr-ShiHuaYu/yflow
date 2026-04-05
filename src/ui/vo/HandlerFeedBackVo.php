<?php

namespace Yflow\ui\vo;

use JsonSerializable;

/**
 * 流程设计器-办理人选择
 *
 *
 */
class HandlerFeedBackVo implements JsonSerializable
{
    /**
     * 入库主键，比如怕角色和用户id重复，可拼接为role:id
     * @var string|null
     */
    private ?string $storageId;

    /**
     * 权限名称，如：管理员、角色管理员、部门管理员等名称
     * @var string|null
     */
    private ?string $handlerName;

    /**
     * 构造函数
     * @param string|null $storageId
     * @param string|null $handlerName
     */
    public function __construct(?string $storageId = null, ?string $handlerName = null)
    {
        $this->storageId = $storageId;
        $this->handlerName = $handlerName;
    }

    /**
     * 获取入库主键
     * @return string|null
     */
    public function getStorageId(): ?string
    {
        return $this->storageId;
    }

    /**
     * 设置入库主键
     * @param string|null $storageId
     * @return $this
     */
    public function setStorageId(?string $storageId): self
    {
        $this->storageId = $storageId;
        return $this;
    }

    /**
     * 获取权限名称
     * @return string|null
     */
    public function getHandlerName(): ?string
    {
        return $this->handlerName;
    }

    /**
     * 设置权限名称
     * @param string|null $handlerName
     * @return $this
     */
    public function setHandlerName(?string $handlerName): self
    {
        $this->handlerName = $handlerName;
        return $this;
    }

    /**
     * 实现 JsonSerializable 接口
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'storageId' => $this->storageId,
            'handlerName' => $this->handlerName,
        ];
    }
}
