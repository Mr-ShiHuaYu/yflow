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

namespace Yflow\core\listener;

use Yflow\core\dto\FlowParams;
use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowInstanceModel;
use Yflow\impl\orm\laravel\FlowNodeModel;
use Yflow\impl\orm\laravel\FlowTaskModel;

/**
 * ListenerVariable - 监听器变量
 *
 *
 */
class ListenerVariable
{

    /**
     * 流程定义
     */
    private ?FlowDefinitionModel $definition;

    /**
     * 流程实例
     */
    private ?FlowInstanceModel $instance;

    /**
     * 监听器对应的节点
     */
    private ?FlowNodeModel $node;

    /**
     * 当前任务
     */
    private ?FlowTaskModel $task;

    /**
     * 下一次执行的节点集合
     */
    private array $nextNodes;

    /**
     * 新创建任务集合
     */
    private ?array $nextTasks;

    /**
     * 流程变量
     */
    private ?array $variable;

    /**
     * 工作流内置参数
     */
    private ?FlowParams $flowParams;

    /**
     * 构造函数
     *
     * @param FlowDefinitionModel|null $definition
     * @param FlowInstanceModel|null $instance
     * @param FlowNodeModel|null $node
     * @param array|null $variable
     * @param FlowTaskModel|null $task
     * @param array|null $nextNodes
     * @param array|null $nextTasks
     * @param FlowParams|null $flowParams
     */
    public function __construct(
        ?FlowDefinitionModel $definition = null,
        ?FlowInstanceModel   $instance = null,
        ?FlowNodeModel       $node = null,
        ?array               $variable = null,
        ?FlowTaskModel       $task = null,
        ?array               $nextNodes = null,
        ?array               $nextTasks = null,
        ?FlowParams          $flowParams = null
    )
    {
        $this->definition = $definition;
        $this->instance   = $instance;
        $this->node       = $node;
        $this->variable   = $variable;
        $this->task       = $task;
        $this->nextNodes  = $nextNodes ?? [];
        $this->nextTasks  = $nextTasks ?? [];
        $this->flowParams = $flowParams;
    }

    /**
     * 获取流程定义
     * @return FlowDefinitionModel|null
     */
    public function getDefinition(): ?FlowDefinitionModel
    {
        return $this->definition;
    }

    /**
     * 设置流程定义
     * @param FlowDefinitionModel|null $definition
     * @return self
     */
    public function setDefinition(?FlowDefinitionModel $definition): self
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * 获取流程实例
     * @return FlowInstanceModel|null
     */
    public function getInstance(): ?FlowInstanceModel
    {
        return $this->instance;
    }

    /**
     * 设置流程实例
     * @param FlowInstanceModel|null $instance
     * @return self
     */
    public function setInstance(?FlowInstanceModel $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * 获取节点
     * @return FlowNodeModel|null
     */
    public function getNode(): ?FlowNodeModel
    {
        return $this->node;
    }

    /**
     * 设置节点
     * @param FlowNodeModel|null $node
     * @return self
     */
    public function setNode(?FlowNodeModel $node): self
    {
        $this->node = $node;
        return $this;
    }

    /**
     * 获取当前任务
     * @return FlowTaskModel|null
     */
    public function getTask(): ?FlowTaskModel
    {
        return $this->task;
    }

    /**
     * 设置当前任务
     * @param FlowTaskModel|null $task
     * @return self
     */
    public function setTask(?FlowTaskModel $task): self
    {
        $this->task = $task;
        return $this;
    }

    /**
     * 获取下一个节点列表
     * @return array
     */
    public function getNextNodes(): array
    {
        return $this->nextNodes;
    }

    /**
     * 设置下一个节点列表
     * @param array|null $nextNodes
     * @return self
     */
    public function setNextNodes(?array $nextNodes): self
    {
        $this->nextNodes = $nextNodes ?? [];
        return $this;
    }

    /**
     * 获取下一个任务列表
     * @return array
     */
    public function getNextTasks(): array
    {
        return $this->nextTasks;
    }

    /**
     * 设置下一个任务列表
     *
     * @param array|null $nextTasks 下一个任务列表
     * @return self
     */
    public function setNextTasks(?array $nextTasks): self
    {
        $this->nextTasks = $nextTasks;
        return $this;
    }

    /**
     * 获取流程变量
     * @return array|null
     */
    public function getVariable(): ?array
    {
        return $this->variable;
    }

    /**
     * 设置流程变量
     * @param array|null $variable
     * @return self
     */
    public function setVariable(?array $variable): self
    {
        $this->variable = $variable;
        return $this;
    }

    /**
     * 获取流程参数
     * @return FlowParams|null
     */
    public function getFlowParams(): ?FlowParams
    {
        return $this->flowParams;
    }

    /**
     * 设置流程参数
     * @param FlowParams|null $flowParams
     * @return self
     */
    public function setFlowParams(?FlowParams $flowParams): self
    {
        $this->flowParams = $flowParams;
        return $this;
    }

    /**
     * 转换为字符串表示
     * @return string
     */
    public function __toString(): string
    {
        return "ListenerVariable{" .
            "definition=" . json_encode($this->definition) .
            ", instance=" . json_encode($this->instance) .
            ", node=" . json_encode($this->node) .
            ", task=" . json_encode($this->task) .
            ", nextNodes=" . json_encode($this->nextNodes) .
            ", nextTasks=" . json_encode($this->nextTasks) .
            ", variable=" . json_encode($this->variable) .
            ", flowParams=" . json_encode($this->flowParams) .
            '}';
    }
}
