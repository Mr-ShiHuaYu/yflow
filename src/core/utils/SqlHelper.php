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
 * SQL 辅助类
 *
 *
 * @since 2023/5/3 15:22
 */
class SqlHelper
{
    private function __construct()
    {
    }

    /**
     * 判断数据库操作是否成功
     *
     * @param int|null $result 数据库操作返回影响条数
     * @return bool
     */
    public static function retBool(?int $result): bool
    {
        return $result !== null && $result >= 1;
    }

    /**
     * 判断数据库操作是否成功
     *
     * @param int|null $result 数据库操作返回影响条数
     * @return bool
     */
    public static function retBoolLong(?int $result): bool
    {
        return self::retBool($result);
    }

    /**
     * 返回 SelectCount 执行结果
     *
     * @param int|null $result 查询结果
     * @return int
     */
    public static function retCount(?int $result): int
    {
        return ($result === null) ? 0 : $result;
    }
}
