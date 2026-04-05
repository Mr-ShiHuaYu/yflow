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

namespace Yflow\core\dto;


/**
 * DefChart - 流程图所需数据集合
 *
 *
 * @since 2023/3/30 14:27
 */
class DefChart
{

    /**
     * 流程图所需的流程定义
     */
    public DefJson $defJson;

    /**
     * 流程图所需的流程节点
     */
    public array $nodeJsonList = [];

    /**
     * 流程图所需的流程跳转
     */
    public array $skipJsonList = [];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->defJson      = new DefJson();
        $this->nodeJsonList = [];
        $this->skipJsonList = [];
    }

    /**
     * 获取流程定义
     * @return DefJson
     */
    public function getDefJson(): DefJson
    {
        return $this->defJson;
    }

    /**
     * 设置流程定义
     * @param DefJson $defJson
     * @return self
     */
    public function setDefJson(DefJson $defJson): self
    {
        $this->defJson = $defJson;
        return $this;
    }

    /**
     * 获取流程节点列表
     * @return array
     */
    public function getNodeJsonList(): array
    {
        return $this->nodeJsonList;
    }

    /**
     * 设置流程节点列表
     * @param array $nodeJsonList
     * @return self
     */
    public function setNodeJsonList(array $nodeJsonList): self
    {
        $this->nodeJsonList = $nodeJsonList;
        return $this;
    }

    /**
     * 获取流程跳转列表
     * @return array
     */
    public function getSkipJsonList(): array
    {
        return $this->skipJsonList;
    }

    /**
     * 设置流程跳转列表
     * @param array $skipJsonList
     * @return self
     */
    public function setSkipJsonList(array $skipJsonList): self
    {
        $this->skipJsonList = $skipJsonList;
        return $this;
    }
}
