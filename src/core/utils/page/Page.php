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

namespace Yflow\core\utils\page;

/**
 * 分页类
 *
 *
 * @since 2023/5/17 1:28
 */
class Page implements OrderBy
{
    /**
     * 当前记录起始索引
     */
    private int $pageNum = 1;

    /**
     * 每页显示记录数
     */
    private int $pageSize = 10;

    /**
     * 数据
     */
    private array $list;

    /**
     * 总量
     */
    private int $total;

    /**
     * 排序列
     */
    private ?string $orderBy = null;

    /**
     * 排序的方向 desc 或者 asc
     */
    private string $isAsc = 'ASC';

    public function __construct(
        int     $pageNum = 1,
        int     $pageSize = 10,
        ?string $orderBy = null,
        ?string $isAsc = null
    )
    {
        $this->pageNum  = $pageNum;
        $this->pageSize = $pageSize;
        $this->orderBy  = $orderBy;
        if ($isAsc !== null) {
            $this->isAsc = $isAsc;
        }
        $this->list  = [];
        $this->total = 0;
    }

    /**
     * 创建空分页对象
     *
     * @return Page
     */
    public static function empty(): Page
    {
        return new Page(1, 10);
    }

    /**
     * 创建分页对象
     *
     * @param int|null $pageNum 当前页码
     * @param int|null $size 每页显示记录数
     * @return Page
     */
    public static function pageOf(?int $pageNum, ?int $size): Page
    {
        return new Page($pageNum ?? 1, $size ?? 10);
    }

    /**
     * 创建带数据的分页对象
     *
     * @param array $list 数据
     * @param int $total 总量
     * @return Page
     */
    public static function of(array $list, int $total): Page
    {
        $page = new Page();
        $page->setList($list);
        $page->setTotal($total);
        return $page;
    }

    /**
     * 获取排序字段
     *
     * @return string|null 排序字段
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * 设置排序字段
     *
     * @param string|null $orderBy 排序字段
     * @return Page
     */
    public function setOrderBy(?string $orderBy): Page
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * 获取排序方向
     *
     * @return string 排序方向
     */
    public function getIsAsc(): string
    {
        return $this->isAsc;
    }

    /**
     * 设置排序方向
     *
     * @param string $isAsc 排序方向
     * @return Page
     */
    public function setIsAsc(string $isAsc): Page
    {
        $this->isAsc = $isAsc;
        return $this;
    }

    /**
     * 获取当前页码
     *
     * @return int
     */
    public function getPageNum(): int
    {
        return $this->pageNum;
    }

    /**
     * 设置当前页码
     *
     * @param int $pageNum 当前页码
     * @return Page
     */
    public function setPageNum(int $pageNum): Page
    {
        $this->pageNum = $pageNum;
        return $this;
    }

    /**
     * 获取每页显示记录数
     *
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * 设置每页显示记录数
     *
     * @param int $pageSize 每页显示记录数
     * @return Page
     */
    public function setPageSize(int $pageSize): Page
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * 获取数据列表
     *
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * 设置数据列表
     *
     * @param array $list 数据列表
     * @return Page
     */
    public function setList(array $list): Page
    {
        $this->list = $list;
        return $this;
    }

    /**
     * 获取总记录数
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * 设置总记录数
     *
     * @param int $total 总记录数
     * @return Page
     */
    public function setTotal(int $total): Page
    {
        $this->total = $total;
        return $this;
    }

    /**
     * 获取总页数
     *
     * @return int
     */
    public function getPages(): int
    {
        if ($this->pageSize <= 0) {
            return 0;
        }
        return (int)ceil($this->total / $this->pageSize);
    }

    /**
     * 是否有上一页
     *
     * @return bool
     */
    public function hasPrevious(): bool
    {
        return $this->pageNum > 1;
    }

    /**
     * 是否有下一页
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->pageNum < $this->getPages();
    }
}
