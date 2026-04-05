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
 * FlowPage - 表格分页数据对象
 *
 * @author ruoyi
 */
class FlowPage
{

    /**
     * 总记录数
     */
    public int $total = 0;

    /**
     * 列表数据
     */
    public array $rows = [];

    /**
     * 消息状态码
     */
    public int $code = 0;

    /**
     * 消息内容
     */
    public ?string $msg = null;

    /**
     * 构造函数
     *
     * @param array $list 列表数据
     * @param int $total 总记录数
     */
    public function __construct(array $list = [], int $total = 0)
    {
        $this->rows  = $list;
        $this->total = $total;
    }

    /**
     * 获取总记录数
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * 设置总记录数
     * @param int $total
     * @return self
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    /**
     * 获取列表数据
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * 设置列表数据
     * @param array $rows
     * @return self
     */
    public function setRows(array $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * 获取消息状态码
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * 设置消息状态码
     * @param int $code
     * @return self
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 获取消息内容
     * @return string|null
     */
    public function getMsg(): ?string
    {
        return $this->msg;
    }

    /**
     * 设置消息内容
     * @param string|null $msg
     * @return self
     */
    public function setMsg(?string $msg): self
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * Serialize the object
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            'total' => $this->total,
            'rows'  => $this->rows,
            'code'  => $this->code,
            'msg'   => $this->msg
        ]);
    }

    /**
     * Unserialize the object
     * @param string $data
     * @return void
     */
    public function unserialize(string $data): void
    {
        $unserialized = unserialize($data);
        $this->total  = $unserialized['total'];
        $this->rows   = $unserialized['rows'];
        $this->code   = $unserialized['code'];
        $this->msg    = $unserialized['msg'];
    }
}
