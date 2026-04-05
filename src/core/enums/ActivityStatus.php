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

namespace Yflow\core\enums;

/**
 * 激活状态
 *
 *
 * @since 2025/6/25
 */
enum ActivityStatus: int
{
    /**
     * 挂起
     */
    case SUSPENDED = 0;

    /**
     * 激活
     */
    case ACTIVITY = 1;

    /**
     * 判断流程是否激活
     */
    public static function isActivity(?int $key): bool
    {
        return $key === self::ACTIVITY->value;
    }

    /**
     * 判断流程是否挂起
     */
    public static function isSuspended(?int $key): bool
    {
        return $key === self::SUSPENDED->value;
    }
}
