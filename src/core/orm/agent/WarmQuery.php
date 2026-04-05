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

namespace Yflow\core\orm\agent;

use Yflow\core\orm\service\IWarmService;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\page\OrderBy;
use Yflow\core\utils\page\Page;


/**
 * 查询代理层处理
 *
 *
 * @since 2023-03-17
 */
class WarmQuery implements OrderBy
{
    /**
     * 排序字段
     */
    private ?string $orderBy = null;

    /**
     * 排序的方向 desc 或者 asc
     */
    private string $isAsc = "ASC";

    /**
     * 服务层对象
     */
    private IWarmService $warmService;

    /**
     * 构造函数
     *
     * @param IWarmService $warmService 服务层对象
     */
    public function __construct(IWarmService $warmService)
    {
        $this->warmService = $warmService;
    }

    /**
     * 获取服务层对象
     *
     * @return IWarmService
     */
    public function getWarmService(): IWarmService
    {
        return $this->warmService;
    }

    /**
     * 设置服务层对象
     *
     * @param IWarmService $warmService 服务层对象
     * @return WarmQuery
     */
    public function setWarmService(IWarmService $warmService): self
    {
        $this->warmService = $warmService;
        return $this;
    }

    /**
     * 分页查询
     *
     * @param mixed $entity 查询实体
     * @param Page|null $page 分页对象
     * @return Page 分页结果
     */
    public function page(mixed $entity, ?Page $page = null): Page
    {
        if (ObjectUtil::isNull($page)) {
            $page = new Page(1, 10, $this->orderBy, $this->isAsc);
        }
        return $this->warmService->page($entity, $page->setOrderBy($this->orderBy)->setIsAsc($this->isAsc));
    }

    /**
     * 查询列表
     *
     * @param mixed $entity 查询实体
     * @return array 结果列表
     */
    public function list(mixed $entity): array
    {
        return $this->warmService->listWithQuery($entity, $this);
    }

    /**
     * 查询单条记录
     *
     * @param mixed $entity 查询实体
     * @return mixed|null 查询结果
     */
    public function getOne(mixed $entity): mixed
    {
        $list = $this->warmService->listWithQuery($entity, $this);
        return CollUtil::getOne($list);
    }

    /**
     * id 设置正序排列
     *
     * @return WarmQuery
     */
    public function orderById(): self
    {
        $this->orderBy = "id";
        return $this;
    }

    /**
     * 创建时间设置正序排列
     *
     * @return WarmQuery
     */
    public function orderByCreateTime(): self
    {
        $this->orderBy = "create_time";
        return $this;
    }

    /**
     * 更新时间设置正序排列
     *
     * @return WarmQuery
     */
    public function orderByUpdateTime(): self
    {
        $this->orderBy = "update_time";
        return $this;
    }

    /**
     * 设置倒序排列
     *
     * @return WarmQuery
     */
    public function desc(): self
    {
        $this->isAsc = "DESC";
        return $this;
    }

    /**
     * 设置字段正序排列
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery
     */
    public function orderByAsc(string $orderByField): self
    {
        $this->orderBy = $orderByField;
        $this->isAsc   = "ASC";
        return $this;
    }

    /**
     * 设置字段倒序排列
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery
     */
    public function orderByDesc(string $orderByField): self
    {
        $this->orderBy = $orderByField;
        $this->isAsc   = "DESC";
        return $this;
    }

    /**
     * 用户自定义排序方案
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery
     */
    public function orderBy(string $orderByField): self
    {
        $this->orderBy = $orderByField;
        return $this;
    }

    /**
     * 获取排序字段
     *
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * 获取排序方向
     *
     * @return string
     */
    public function getIsAsc(): string
    {
        return $this->isAsc;
    }

    /**
     * 设置排序方向
     *
     * @param string $isAsc 排序方向
     * @return WarmQuery
     */
    public function setIsAsc(string $isAsc): self
    {
        $this->isAsc = $isAsc;
        return $this;
    }
}
