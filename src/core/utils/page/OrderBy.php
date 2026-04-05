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

namespace Yflow\core\utils\page;

/**
 * 排序接口
 *
 *
 * @since 2023/5/17 1:28
 */
interface OrderBy
{
    /**
     * 获取排序字段
     *
     * @return string|null 排序字段
     */
    public function getOrderBy(): ?string;

    /**
     * 排序策略，是否 Asc
     *
     * @return string 是的话返回 asc，不是 desc
     */
    public function getIsAsc(): string;

    public const ASC = 'asc';
}
