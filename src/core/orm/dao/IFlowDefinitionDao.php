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
 * IFlowDefinitionDao - 流程定义 Dao 接口，不同的 orm 扩展包实现它
 */
interface IFlowDefinitionDao extends IFlowBaseDao
{

    /**
     * 根据编码批量查询
     *
     * @param array $flowCodeList 流程编码集
     * @return IFlowDefinitionDao[] 查询结果
     */
    public function queryByCodeList(array $flowCodeList): array;

    /**
     * 根据 ID 批量修改发布状态
     *
     * @param array $ids ids
     * @param int $publishStatus 发布状态 (9=已失效；0=未发布；1=已发布)
     * @return void
     * @see \Yflow\core\enums\PublishStatus
     */
    public function updatePublishStatus(array $ids, int $publishStatus): void;
}
