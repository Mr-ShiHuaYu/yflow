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

namespace Yflow\core\service;

use Yflow\core\orm\dao\IFlowSkipDao;
use Yflow\core\orm\service\IWarmService;

/**
 * SkipService - 节点跳转关联 Service 接口
 *
 *
 * @since 2023-03-29
 */
interface SkipService extends IWarmService
{

    /**
     * 批量删除节点跳转关联
     *
     * @param array $defIds 需要删除的数据主键集合
     * @return int 结果
     */
    public function deleteSkipByDefIds(array $defIds): int;

    /**
     * 根据流程定义 id 查询节点跳转线
     *
     * @param int $definitionId 流程定义 id
     * @return array<IFlowSkipDao>
     */
    public function getByDefId(int $definitionId): array;

    /**
     * 根据流程定义 id 和节点编码查询节点跳转线
     *
     * @param int $definitionId 流程定义 id
     * @param string $nowNodeCode 其实节点编码
     * @return array<IFlowSkipDao>
     */
    public function getByDefIdAndNowNodeCode(int $definitionId, string $nowNodeCode): array;
}
