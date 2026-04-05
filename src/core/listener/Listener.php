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
 * Listener - 监听器接口
 *
 *
 */
interface Listener
{

    /**
     * 开始监听器，任务开始办理时执行
     */
    public const LISTENER_START = 'start';

    /**
     * 分派监听器，动态修改代办任务信息
     */
    public const LISTENER_ASSIGNMENT = 'assignment';

    /**
     * 完成监听器，当前任务完成后执行
     */
    public const LISTENER_FINISH = 'finish';

    /**
     * 创建监听器，任务创建时执行
     */
    public const LISTENER_CREATE = 'create';

    /**
     * 表单数据加载监听器，1.3.0 内置表单使用
     */
    public const LISTENER_FORM_LOAD = 'formLoad';

    /**
     * 通知监听器
     *
     * @param ListenerVariable $variable 监听器变量
     * @return void
     */
    public function notify(ListenerVariable $variable): void;
}
