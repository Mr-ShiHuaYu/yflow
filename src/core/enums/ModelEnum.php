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
 * 设计器模型（CLASSICS 经典模型 MIMIC 仿钉钉模型）
 *
 *
 * @since 2025/6/25
 */
enum ModelEnum: string
{
    /**
     * 经典模型
     */
    case CLASSICS = 'CLASSICS';

    /**
     * 仿钉钉模型
     */
    case MIMIC = 'MIMIC';
}
