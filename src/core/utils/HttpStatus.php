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

namespace Yflow\core\utils;

/**
 * HTTP 状态码常量类
 *
 *
 */
class HttpStatus
{
    /**
     * 操作成功
     */
    public const SUCCESS = 200;

    /**
     * 对象创建成功
     */
    public const CREATED = 201;

    /**
     * 请求已经被接受
     */
    public const ACCEPTED = 202;

    /**
     * 操作已经执行成功，但是没有返回数据
     */
    public const NO_CONTENT = 204;

    /**
     * 资源已被移除
     */
    public const MOVED_PERM = 301;

    /**
     * 重定向
     */
    public const SEE_OTHER = 303;

    /**
     * 资源没有被修改
     */
    public const NOT_MODIFIED = 304;

    /**
     * 参数列表错误（缺少，格式不匹配）
     */
    public const BAD_REQUEST = 400;

    /**
     * 未授权
     */
    public const UNAUTHORIZED = 401;

    /**
     * 访问受限，授权过期
     */
    public const FORBIDDEN = 403;

    /**
     * 资源，服务未找到
     */
    public const NOT_FOUND = 404;

    /**
     * 不允许的 http 方法
     */
    public const BAD_METHOD = 405;

    /**
     * 资源冲突，或者资源被锁
     */
    public const CONFLICT = 409;

    /**
     * 不支持的数据，媒体类型
     */
    public const UNSUPPORTED_TYPE = 415;

    /**
     * 系统内部错误
     */
    public const ERROR = 500;

    /**
     * 接口未实现
     */
    public const NOT_IMPLEMENTED = 501;

    /**
     * 系统警告消息
     */
    public const WARN = 601;
}
