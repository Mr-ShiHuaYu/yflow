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

use Exception;
use Throwable;

/**
 * 异常工具类
 *
 *
 */
class ExceptionUtil
{
    /**
     * 获取 exception 的详细错误信息
     *
     * @param Throwable $e 异常对象
     * @return string 详细错误信息
     */
    public static function getExceptionMessage(Throwable $e): string
    {
        return $e->getMessage() . "\n" . $e->getTraceAsString();
    }

    /**
     * 处理消息是否显示中文
     *
     * @param string $msg 自定义消息
     * @param Exception $e 异常对象
     * @return string 处理后的消息
     */
    public static function handleMsg(string $msg, Exception $e): string
    {
        if (StringUtils::isEmpty($msg)) {
            return $e->getMessage();
        } else {
            return $msg . ": " . $e->getMessage();
        }
    }
}
