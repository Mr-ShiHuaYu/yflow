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

namespace Yflow\core\orm\dao;

/**
 * IFlowInstanceDao - 流程实例 Mapper 接口
 *
 *
 * @since 2023-03-29
 */
interface IFlowInstanceDao extends IFlowBaseDao
{

    /**
     * 根据流程定义 ID 查询流程实例集合
     *
     * @param array $defIds 流程定义 ID 集合
     * @return array<IFlowInstanceDao> 流程实例集合
     */
    public function getByDefIds(array $defIds): array;
}
