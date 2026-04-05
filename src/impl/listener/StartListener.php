<?php

namespace Yflow\impl\listener;

use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;
use Yflow\impl\orm\laravel\FlowInstanceModel;

class StartListener implements Listener
{
    /**
     * @param ListenerVariable $variable
     */
    public function notify(ListenerVariable $variable): void
    {
        dump("节点开始监听器......");
        /**
         * @var FlowInstanceModel $instance
         */
        $instance = $variable->getInstance();
        $variableMap = $variable->getVariable();
        dump("节点开始监听器结束......");
    }
}
