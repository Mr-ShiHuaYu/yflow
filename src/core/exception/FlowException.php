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

namespace Yflow\core\exception;

use Exception;
use Throwable;

/**
 * 流程异常类
 *
 *
 */
class FlowException extends Exception
{
    /**
     * 错误码
     */
    protected $code = 500;

    /**
     * 错误详情
     */
    protected ?string $detailMessage = null;

    /**
     * 构造函数
     */
    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * 设置错误详情
     */
    public function setDetailMessage(string $detailMessage): self
    {
        $this->detailMessage = $detailMessage;
        return $this;
    }

    /**
     * 获取错误详情
     */
    public function getDetailMessage(): ?string
    {
        return $this->detailMessage;
    }

    /**
     * 设置错误消息
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 设置错误码
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }
}
