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
 * IFlowFormDao - 流程表单 Dao 接口，不同的 orm 扩展包实现它
 *
 * @author vanlin
 * @since 2024/8/19 10:24
 */
interface IFlowFormDao extends IFlowBaseDao
{

    /**
     * 根据编码批量查询
     *
     * @param array $formCodeList 表单编码集
     * @return array<IFlowFormDao> 查询结果
     */
    public function queryByCodeList(array $formCodeList): array;
}
