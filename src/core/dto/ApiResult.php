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
 * API 响应结果
 *
 * @author ruoyi
 */
class ApiResult
{
    /**
     * 成功状态码
     */
    public const SUCCESS = 200;

    /**
     * 失败状态码
     */
    public const FAIL = 500;

    /**
     * 状态码
     */
    public int $code;

    /**
     * 消息
     */
    public string $msg;

    /**
     * 数据
     */
    public mixed $data = null;

    /**
     * 构造函数
     */
    public function __construct(int $code = self::SUCCESS, string $msg = '操作成功', mixed $data = null)
    {
        $this->code = $code;
        $this->msg  = $msg;
        $this->data = $data;
    }

    /**
     * 成功响应
     */
    public static function ok(mixed $data = null, string $msg = '操作成功'): self
    {
        return new self(self::SUCCESS, $msg, $data);
    }

    /**
     * 失败响应
     */
    public static function fail(string $msg = '操作失败', int $code = self::FAIL, mixed $data = null): self
    {
        if ($data === null) {
            $data = $msg;
            $msg  = '操作失败';
        }
        return new self($code, $msg, $data);
    }

    /**
     * 自定义状态码失败响应
     */
    public static function failWithCode(int $code, string $msg): self
    {
        return new self($code, $msg, null);
    }

    /**
     * 判断是否错误
     */
    public static function isError(ApiResult $ret): bool
    {
        return !self::isSuccess($ret);
    }

    /**
     * 判断是否成功
     */
    public static function isSuccess(ApiResult $ret): bool
    {
        return self::SUCCESS === $ret->code;
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'msg'  => $this->msg,
            'data' => $this->data,
        ];
    }

    /**
     * 转换为 JSON
     */
    public function toJson(int $options = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->toArray(), $options);
    }
}
