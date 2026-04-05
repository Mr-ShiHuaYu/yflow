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

/**
 * 类工具类（PHP 版本简化实现）
 *
 *
 */
class ClassUtil
{
    private function __construct()
    {
    }

    /**
     * 通过类名获取对象实例
     *
     * @param string $className 类名
     * @return object|null
     */
    public static function getClazz(string $className): ?object
    {
        if (class_exists($className)) {
            return new $className();
        }
        return null;
    }

    /**
     * 通过反射实现对象克隆（浅拷贝）
     *
     * @param object|null $origin 原始对象
     * @return object|null 克隆结果
     */
    public static function clone(?object $origin): ?object
    {
        if ($origin === null) {
            return null;
        }

        try {
            // 使用 PHP 内置克隆
            return clone $origin;
        } catch (Exception $e) {
            return null;
        }
    }

}
