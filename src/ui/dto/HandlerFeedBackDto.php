<?php

namespace Yflow\ui\dto;


/**
 * 流程设计器-办理人选择
 *
 *
 */
class HandlerFeedBackDto
{
    /**
     * 入库主键集合，比如怕角色和用户id重复，可拼接为role:id
     * @var array<string>|null
     */
    private ?array $storageIds = null;

    /**
     * 获取入库主键集合
     * @return array<string>|null
     */
    public function getStorageIds(): ?array
    {
        return $this->storageIds;
    }

    /**
     * 设置入库主键集合
     * @param array<string>|null $storageIds
     * @return $this
     */
    public function setStorageIds(?array $storageIds): self
    {
        $this->storageIds = $storageIds;
        return $this;
    }
}
