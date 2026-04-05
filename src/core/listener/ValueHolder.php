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

namespace Yflow\core\listener;

/**
 * 值持有者 - 用于存储监听器相关信息
 *
 *
 */
class ValueHolder
{
    /**
     * 路径
     */
    private ?string $path = null;

    /**
     * 监听器
     */
    private ?Listener $listener = null;

    /**
     * 参数
     */
    private ?string $params = null;

    /**
     * 获取路径
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * 设置路径
     *
     * @param string|null $path
     * @return self
     */
    public function setPath(?string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * 获取监听器
     *
     * @return Listener|null
     */
    public function getListener(): ?Listener
    {
        return $this->listener;
    }

    /**
     * 设置监听器
     *
     * @param Listener|null $listener
     * @return self
     */
    public function setListener(?Listener $listener): self
    {
        $this->listener = $listener;
        return $this;
    }

    /**
     * 获取参数
     *
     * @return string|null
     */
    public function getParams(): ?string
    {
        return $this->params;
    }

    /**
     * 设置参数
     *
     * @param string|null $params
     * @return self
     */
    public function setParams(?string $params): self
    {
        $this->params = $params;
        return $this;
    }
}
