<?php

namespace Yflow\impl\listener;

use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;
use Yflow\impl\orm\laravel\FlowInstanceModel;

class DefCreateListener implements Listener
{
    /**
     * @param ListenerVariable $variable
     */
    public function notify(ListenerVariable $variable): void
    {
        dump("流程创建监听器......");
        /**
         * @var FlowInstanceModel $instance
         */
        $instance = $variable->getInstance();
        $testLeaveMap = $variable->getVariable();
        dump("流程创建监听器结束......");
    }
}
