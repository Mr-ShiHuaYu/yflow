<?php

namespace Yflow\ui\vo;

use JsonSerializable;

/**
 * 流程配置vo
 * 因为属性都是 private，所以需要实现 JsonSerializable 接口,才能被 json_encode 序列化
 *
 */
class WarmFlowVo implements JsonSerializable
{
    /**
     * 如果需要工作流共享业务系统权限，默认Authorization
     * @var array<string>|null
     */
    private ?array $tokenNameList = null;

    /**
     * 获取token名称列表
     * @return array<string>|null
     */
    public function getTokenNameList(): ?array
    {
        return $this->tokenNameList;
    }

    /**
     * 设置token名称列表
     * @param array<string>|null $tokenNameList
     * @return $this
     */
    public function setTokenNameList(?array $tokenNameList): self
    {
        $this->tokenNameList = $tokenNameList;
        return $this;
    }

    /**
     * 实现 JsonSerializable 接口
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'tokenNameList' => $this->tokenNameList,
        ];
    }
}
