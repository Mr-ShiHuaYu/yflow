<?php

namespace Yflow\core\service\impl;

use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowInstanceModel;
use Yflow\impl\orm\laravel\FlowNodeModel;
use Yflow\impl\orm\laravel\FlowTaskModel;

class TaskCheckResult
{
    public FlowInstanceModel $instance;

    public FlowNodeModel $nowNode;

    public FlowTaskModel $task;

    public FlowDefinitionModel $definition;

    public function __construct(FlowInstanceModel $instance, FlowNodeModel $nowNode, FlowTaskModel $task, FlowDefinitionModel $definition)
    {
        $this->instance   = $instance;
        $this->nowNode    = $nowNode;
        $this->task       = $task;
        $this->definition = $definition;
    }
}
