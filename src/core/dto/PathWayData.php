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
 * PathWayData - 办理过程中途径数据，用于渲染流程图
 *
 *
 * @since 2025/1/4
 */
class PathWayData
{

    /**
     * 流程定义 id
     */
    public ?int $defId = null;

    /**
     * 流程实例 id
     */
    public ?int $insId = null;

    /**
     * 跳转类型（PASS 审批通过 REJECT 退回）
     */
    public ?string $skipType = null;

    /**
     * 目标结点集合
     */
    public array $targetNodes = [];

    /**
     * 途径结点集合
     */
    public array $pathWayNodes = [];

    /**
     * 途径流程跳转线
     */
    public array $pathWaySkips = [];

    /**
     * 获取流程定义 id
     * @return int|null
     */
    public function getDefId(): ?int
    {
        return $this->defId;
    }

    /**
     * 设置流程定义 id
     * @param int|null $defId
     * @return self
     */
    public function setDefId(?int $defId): self
    {
        $this->defId = $defId;
        return $this;
    }

    /**
     * 获取流程实例 id
     * @return int|null
     */
    public function getInsId(): ?int
    {
        return $this->insId;
    }

    /**
     * 设置流程实例 id
     * @param int|null $insId
     * @return self
     */
    public function setInsId(?int $insId): self
    {
        $this->insId = $insId;
        return $this;
    }

    /**
     * 获取跳转类型
     * @return string|null
     */
    public function getSkipType(): ?string
    {
        return $this->skipType;
    }

    /**
     * 设置跳转类型
     * @param string|null $skipType
     * @return self
     */
    public function setSkipType(?string $skipType): self
    {
        $this->skipType = $skipType;
        return $this;
    }

    /**
     * 获取目标结点集合
     * @return array
     */
    public function getTargetNodes(): array
    {
        return $this->targetNodes;
    }

    /**
     * 设置目标结点集合
     * @param array $targetNodes
     * @return self
     */
    public function setTargetNodes(array $targetNodes): self
    {
        $this->targetNodes = $targetNodes;
        return $this;
    }

    /**
     * 获取途径结点集合
     * @return array
     */
    public function getPathWayNodes(): array
    {
        return $this->pathWayNodes;
    }

    /**
     * 设置途径结点集合
     * @param array $pathWayNodes
     * @return self
     */
    public function setPathWayNodes(array $pathWayNodes): self
    {
        $this->pathWayNodes = $pathWayNodes;
        return $this;
    }

    /**
     * 获取途径流程跳转线
     * @return array
     */
    public function getPathWaySkips(): array
    {
        return $this->pathWaySkips;
    }

    /**
     * 设置途径流程跳转线
     * @param array $pathWaySkips
     * @return self
     */
    public function setPathWaySkips(array $pathWaySkips): self
    {
        $this->pathWaySkips = $pathWaySkips;
        return $this;
    }
}
