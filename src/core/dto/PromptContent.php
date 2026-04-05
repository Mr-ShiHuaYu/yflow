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
 * PromptContent - 提示信息
 *
 *
 * @since 2025/6/5
 */
class PromptContent
{

    /**
     * 弹窗样式
     */
    public ?array $dialogStyle = null;

    /**
     * 提示信息
     */
    public array $info = [];

    /**
     * 构造函数
     *
     * @param array|null $dialogStyle
     * @param array|null $info
     */
    public function __construct(?array $dialogStyle = null, ?array $info = null)
    {
        $this->dialogStyle = $dialogStyle;
        $this->info        = $info ?? [];
    }

    /**
     * 获取弹窗样式
     * @return array|null
     */
    public function getDialogStyle(): ?array
    {
        return $this->dialogStyle;
    }

    /**
     * 设置弹窗样式
     * @param array|null $dialogStyle
     * @return self
     */
    public function setDialogStyle(?array $dialogStyle): self
    {
        $this->dialogStyle = $dialogStyle;
        return $this;
    }

    /**
     * 获取提示信息
     * @return array
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * 设置提示信息
     * @param array $info
     * @return self
     */
    public function setInfo(array $info): self
    {
        $this->info = $info;
        return $this;
    }

    /**
     * 添加提示信息项
     * @param InfoItem $infoItem
     * @return self
     */
    public function addInfoItem(InfoItem $infoItem): self
    {
        $this->info[] = $infoItem;
        return $this;
    }
}
