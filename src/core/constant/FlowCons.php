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

namespace Yflow\core\constant;

/**
 * FlowCons - y-flow 常量
 *
 *
 */
class FlowCons
{

    /**
     * 分隔符
     */
    public const SPLIT_AT = "@@";

    public const SPLIT_VERTICAL = "|";

    public const DEFAULT        = "default";

    public const SPEL = "spel";

    /**
     * 权限标识中的发起人标识符，办理过程中进行替换
     */
    public const WARMFLOWINITIATOR = "warmFlowInitiator";

    /**
     * 监听器参数
     */
    public const WARM_LISTENER_PARAM = "WarmListenerParam";

    /**
     * 监听器正则表达式模式
     */
    public const LISTENER_PATTERN = '/^([^()]*)(.*)$/';

    /**
     * 雪花 id 14 位
     */
    public const SNOWID14 = "SnowId14";

    /**
     * 雪花 id 15 位
     */
    public const SNOWID15 = "SnowId15";

    /**
     * 雪花 id 19 位
     */
    public const SNOWID19 = "SnowId19";

    /**
     * 表单自定义状态
     * 内置表单
     */
    public const FORM_CUSTOM_Y = "Y";

    /**
     * 外挂表单路径
     */
    public const FORM_CUSTOM_N = "N";

    /**
     * 表单数据
     */
    public const FORM_DATA = "formData";

    public const PREVIOUS = "previous";

    public const SUFFIX = "suffix";
}
