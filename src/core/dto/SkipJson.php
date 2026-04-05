<?php

/*
 *    Copyright 2026, Y-Flow (974988176@qq.com).
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *       https://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace Yflow\core\dto;


/**
 * 节点跳转关联对象Vo
 *
 *
 * @since 2023-03-29
 */
class SkipJson
{

    /**
     * 当前流程节点的编码
     */
    public ?string $nowNodeCode = null;

    /**
     * 下一个流程节点的编码
     */
    public ?string $nextNodeCode = null;

    /**
     * 跳转名称
     */
    public ?string $skipName = null;

    /**
     * 跳转类型（PASS审批通过 REJECT退回）
     */
    public ?string $skipType = null;

    /**
     * 跳转条件
     */
    public ?string $skipCondition = null;

    /**
     * 流程跳转坐标
     */
    public ?string $coordinate = null;

    /**
     * 办理状态: 0未办理 1待办理 2已办理
     */
    public ?int $status = null;

    /**
     * 扩展map，保存业务自定义扩展属性
     */
    public ?array $extMap = null;

    /**
     * 流程图节点提示内容
     */
    public ?array $promptContent = null;

    public ?string $createBy = null;

    public ?string $updateBy = null;

    /**
     * @return string|null
     */
    public function getNowNodeCode(): ?string
    {
        return $this->nowNodeCode;
    }

    /**
     * @param string|null $nowNodeCode
     * @return self
     */
    public function setNowNodeCode(?string $nowNodeCode): self
    {
        $this->nowNodeCode = $nowNodeCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNextNodeCode(): ?string
    {
        return $this->nextNodeCode;
    }

    /**
     * @param string|null $nextNodeCode
     * @return self
     */
    public function setNextNodeCode(?string $nextNodeCode): self
    {
        $this->nextNodeCode = $nextNodeCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSkipName(): ?string
    {
        return $this->skipName;
    }

    /**
     * @param string|null $skipName
     * @return self
     */
    public function setSkipName(?string $skipName): self
    {
        $this->skipName = $skipName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSkipType(): ?string
    {
        return $this->skipType;
    }

    /**
     * @param string|null $skipType
     * @return self
     */
    public function setSkipType(?string $skipType): self
    {
        $this->skipType = $skipType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSkipCondition(): ?string
    {
        return $this->skipCondition;
    }

    /**
     * @param string|null $skipCondition
     * @return self
     */
    public function setSkipCondition(?string $skipCondition): self
    {
        $this->skipCondition = $skipCondition;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCoordinate(): ?string
    {
        return $this->coordinate;
    }

    /**
     * @param string|null $coordinate
     * @return self
     */
    public function setCoordinate(?string $coordinate): self
    {
        $this->coordinate = $coordinate;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     * @return self
     */
    public function setStatus(?int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getExtMap(): ?array
    {
        return $this->extMap;
    }

    /**
     * @param array|null $extMap
     * @return self
     */
    public function setExtMap(?array $extMap): self
    {
        $this->extMap = $extMap;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPromptContent(): ?array
    {
        return $this->promptContent;
    }

    /**
     * @param array|null $promptContent
     * @return self
     */
    public function setPromptContent(?array $promptContent): self
    {
        $this->promptContent = $promptContent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    /**
     * @param string|null $createBy
     * @return self
     */
    public function setCreateBy(?string $createBy): self
    {
        $this->createBy = $createBy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    /**
     * @param string|null $updateBy
     * @return self
     */
    public function setUpdateBy(?string $updateBy): self
    {
        $this->updateBy = $updateBy;
        return $this;
    }
}
