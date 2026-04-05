<?php

namespace Yflow\impl\listener;

use Yflow\core\listener\GlobalListener;
use Yflow\core\listener\Listener;
use Yflow\core\listener\ListenerVariable;

/**
 * 全局监听器: 整个系统只有一个，任务开始、分派、完成和创建、时期执行
 *
 *
 * @since 2024/11/17
 */
class CustomGlobalListener implements GlobalListener
{
    /**
     * 开始监听器，任务开始办理时执行
     * @param ListenerVariable $listenerVariable 监听器变量
     */
    public function start(ListenerVariable $listenerVariable): void
    {
        dump("全局开始监听器开始执行......");
        dump("全局开始监听器执行结束......");
    }

    /**
     * 分派监听器，动态修改代办任务信息
     * @param ListenerVariable $listenerVariable 监听器变量
     */
    public function assignment(ListenerVariable $listenerVariable): void
    {
        dump("全局分派监听器开始执行......");
        dump("全局分派监听器执行结束......");
    }

    /**
     * 完成监听器，当前任务完成后执行
     * @param ListenerVariable $listenerVariable 监听器变量
     */
    public function finish(ListenerVariable $listenerVariable): void
    {
        dump("全局完成监听器开始执行......");
        dump("全局完成监听器执行结束......");
    }

    /**
     * 创建监听器，任务创建时执行
     * @param ListenerVariable $listenerVariable 监听器变量
     */
    public function create(ListenerVariable $listenerVariable): void
    {
        dump("全局创建监听器开始执行......");
        dump("全局创建监听器执行结束......");
    }

    /**
     * 通知方法
     *
     * @param string $type 监听器类型
     * @param ListenerVariable $listenerVariable 监听器变量
     * @return void
     */
    public function notify(string $type, ListenerVariable $listenerVariable): void
    {
        switch ($type) {
            case Listener::LISTENER_START:
                $this->start($listenerVariable);
                break;
            case Listener::LISTENER_ASSIGNMENT:
                $this->assignment($listenerVariable);
                break;
            case Listener::LISTENER_FINISH:
                $this->finish($listenerVariable);
                break;
            case Listener::LISTENER_CREATE:
                $this->create($listenerVariable);
                break;
        }
    }
}
