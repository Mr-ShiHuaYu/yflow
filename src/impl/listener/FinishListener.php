<?php

namespace Yflow\impl\listener;

use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;
use Yflow\impl\orm\laravel\FlowInstanceModel;

class FinishListener implements Listener
{
    /**
     * @param ListenerVariable $variable
     */
    public function notify(ListenerVariable $variable): void
    {
        dump("节点完成监听器开始......");
        /**
         * @var FlowInstanceModel $instance
         */
        $instance = $variable->getInstance();
        $testLeaveMap = $variable->getVariable();
        dump("节点完成监听器结束......");
    }
}
