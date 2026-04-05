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

use Yflow\core\dto\PathWayData;

/**
 * ChartService - 流程图绘制 Service 接口
 *
 *
 * @since 2024-12-30
 */
interface ChartService
{

    /**
     * 获取流程开启时的流程图元数据
     *
     * @param PathWayData $pathWayData 办理过程中途径数据，用于渲染流程图
     * @return string 流程图元数据 json
     */
    public function startMetadata(PathWayData $pathWayData): string;

    /**
     * 获取流程运行时的流程图元数据
     *
     * @param PathWayData $pathWayData 办理过程中途径数据，用于渲染流程图
     * @return string 流程图元数据 json
     */
    public function skipMetadata(PathWayData $pathWayData): string;

    /**
     * 获取流程图三原色
     *
     * @param string $modelValue 流程模型
     * @return array 流程图颜色列表
     */
    public function getChartRgb(string $modelValue): array;
}
