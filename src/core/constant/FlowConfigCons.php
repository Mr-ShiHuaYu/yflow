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
 * FlowConfigCons - y-flow 配置文件常量
 *
 */
class FlowConfigCons
{

    /**
     * 是否支持任意跳转
     */
    public const BANNER = "y-flow.banner";

    /**
     * 是否开启逻辑删除
     */
    public const LOGIC_DELETE = "y-flow.logic_delete";

    /**
     * ID 生成器类型
     */
    public const KEY_TYPE = "y-flow.key_type";

    /**
     * 逻辑删除字段值
     */
    public const LOGIC_DELETE_VALUE = "y-flow.logic_delete_value";

    /**
     * 逻辑未删除字段
     */
    public const LOGIC_NOT_DELETE_VALUE = "y-flow.logic_not_delete_value";

    /**
     * 数据填充处理类路径
     */
    public const DATA_FILL_HANDLE_PATH = "y-flow.data-fill-handler-path";

    /**
     * 租户处理类路径
     */
    public const TENANT_HANDLER_PATH = "y-flow.tenant_handler_path";

    /**
     * 数据源类型，mybatis 模块对 orm 进一步的封装，由于各数据库分页语句存在差异，
     * 当配置此参数时，以此参数结果为基准，未配置时，取 DataSource 中数据源类型，
     * 兜底为 mysql 数据库
     */
    public const DATA_SOURCE_TYPE = "y-flow.data_source_type";

    /**
     * 是否支持 ui
     */
    public const UI = "y-flow.ui";

    /**
     * 如果需要工作流共享业务系统权限
     */
    public const TOKEN_NAME = "y-flow.token-name";
}
