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

use Yflow\core\FlowEngine;
use Yflow\impl\orm\laravel\FlowDefinitionModel;


/**
 * FlowCombine - 流程数据集合
 *
 *
 * @since 2023/3/30 14:27
 */
class FlowCombine
{

    /**
     * 所有的流程定义
     */
    public FlowDefinitionModel $definition;

    /**
     * 所有的流程节点
     */
    public array $allNodes;

    /**
     * 所有的流程节点跳转关联
     */
    public array $allSkips;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->definition = FlowEngine::newDef();
        $this->allNodes   = [];
        $this->allSkips   = [];
    }

    /**
     * 获取流程定义
     * @return FlowDefinitionModel
     */
    public function getDefinition(): FlowDefinitionModel
    {
        return $this->definition;
    }

    /**
     * 设置流程定义
     * @param FlowDefinitionModel $definition
     * @return self
     */
    public function setDefinition(FlowDefinitionModel $definition): self
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * 获取所有流程节点
     * @return array
     */
    public function getAllNodes(): array
    {
        return $this->allNodes;
    }

    /**
     * 设置所有流程节点
     * @param array $allNodes
     * @return self
     */
    public function setAllNodes(array $allNodes): self
    {
        $this->allNodes = $allNodes;
        return $this;
    }

    /**
     * 获取所有流程跳转
     * @return array
     */
    public function getAllSkips(): array
    {
        return $this->allSkips;
    }

    /**
     * 设置所有流程跳转
     * @param array $allSkips
     * @return self
     */
    public function setAllSkips(array $allSkips): self
    {
        $this->allSkips = $allSkips;
        return $this;
    }
}
