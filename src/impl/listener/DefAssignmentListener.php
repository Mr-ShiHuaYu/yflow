<?php

namespace Yflow\impl\listener;

use Yflow\core\constant\FlowCons;
use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\StringUtils;
use Yflow\impl\orm\laravel\FlowInstanceModel;
use Yflow\impl\orm\laravel\FlowTaskModel;

/**
 * 流程分派监听器
 */
class DefAssignmentListener implements Listener
{
    /**
     * @param ListenerVariable $variable
     */
    public function notify(ListenerVariable $variable): void
    {
        dump("流程分派监听器开始执行......");
        /**
         * @var FlowTaskModel[] $tasks
         */
        $tasks = $variable->getNextTasks();
        /**
         * @var FlowInstanceModel $instance
         */
        $instance = $variable->getInstance();
        if (CollUtil::isNotEmpty($tasks)) {
            foreach ($tasks as $task) {
                $permissionList = $task->getPermissionList();
                // 如果设置了发起人审批，则需要动态替换权限标识
                for ($i = 0; $i < count($permissionList); $i++) {
                    $permission = $permissionList[$i];
                    if (StringUtils::isNotEmpty($permission) && str_contains($permission, FlowCons::WARMFLOWINITIATOR)) {
                        $permissionList[$i] = str_replace(FlowCons::WARMFLOWINITIATOR, $instance->getCreateBy(), $permission);
                    }
                }
            }
        }
        dump("流程分派监听器执行结束......");
    }
}
