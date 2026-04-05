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


/**
 * GlobalListener - 全局监听器接口
 *
 *
 * @since 2024/11/17
 */
interface GlobalListener
{

    /**
     * 开始监听器，任务开始办理时执行
     *
     * @param ListenerVariable $listenerVariable 监听器变量
     * @return void
     */
    public function start(ListenerVariable $listenerVariable): void;

    /**
     * 分派监听器，动态修改代办任务信息
     *
     * @param ListenerVariable $listenerVariable 监听器变量
     * @return void
     */
    public function assignment(ListenerVariable $listenerVariable): void;

    /**
     * 完成监听器，当前任务完成后执行
     *
     * @param ListenerVariable $listenerVariable 监听器变量
     * @return void
     */
    public function finish(ListenerVariable $listenerVariable): void;

    /**
     * 创建监听器，任务创建时执行
     *
     * @param ListenerVariable $listenerVariable 监听器变量
     * @return void
     */
    public function create(ListenerVariable $listenerVariable): void;

    /**
     * 通知方法
     *
     * @param string $type 监听器类型
     * @param ListenerVariable $listenerVariable 监听器变量
     * @return void
     */
    public function notify(string $type, ListenerVariable $listenerVariable): void;
}
