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

namespace Yflow\core\orm\service;

use support\Model;
use Yflow\core\orm\agent\WarmQuery;
use Yflow\core\orm\dao\IFlowBaseDao;
use Yflow\core\utils\page\Page;

/**
 * IWarmService - ORM 服务接口
 *
 * 所有 Service 的父接口，提供基础的 CRUD 能力
 *
 *
 * @since 2023-03-17
 *
 * @template T of IFlowBaseDao
 */
interface IWarmService
{

    /**
     * 获取 DAO 对象
     *
     * @return T DAO 对象
     */
    public function getDao(): IFlowBaseDao;

    /**
     * 根据 ID 查询
     *
     * @param mixed $id 主键
     * @return Model|null 实体对象
     */
    public function getById(mixed $id): ?Model;


    /**
     * 根据 IDs 批量查询
     *
     * @param array $ids 主键集合
     * @return array<Model> 实体列表
     */
    public function getByIds(array $ids): array;

    /**
     * 分页查询
     *
     * @param mixed $entity 查询实体
     * @param Page $page 分页对象
     * @return Page 分页结果
     */
    public function page(mixed $entity, Page $page): Page;

    /**
     * 查询列表
     *
     * @param mixed $entity 查询实体
     * @return array<Model> 结果列表
     */
    public function list(mixed $entity): array;

    /**
     * 查询列表（可排序）
     *
     * @param mixed $entity 查询实体
     * @param WarmQuery|null $query 查询条件（包含排序）
     * @return array<Model> 结果列表
     */
    public function listWithQuery(mixed $entity, ?WarmQuery $query = null): array;

    /**
     * 查询单条记录
     *
     * @param mixed $entity 查询实体
     * @return Model|null 查询结果
     */
    public function getOne(mixed $entity): ?Model;


    /**
     * 获取总数量
     *
     * @param mixed $entity 查询实体
     * @return int 结果数量
     */
    public function selectCount(mixed $entity): int;

    /**
     * 判断是否存在
     *
     * @param mixed $entity 查询实体
     * @return bool 是否存在
     */
    public function exists(mixed $entity): bool;

    /**
     * 新增
     *
     * @param mixed $entity 实体对象
     * @return bool 是否成功
     */
    public function save(mixed $entity): bool;

    /**
     * 根据 ID 修改
     *
     * @param mixed $entity 实体对象
     * @return bool 是否成功
     */
    public function updateById(mixed $entity): bool;

    /**
     * 根据 ID 删除
     *
     * @param mixed $id 主键
     * @return bool 是否成功
     */
    public function removeById(mixed $id): bool;

    /**
     * 根据实体删除
     *
     * @param mixed $entity 实体对象
     * @return bool 是否成功
     */
    public function remove(mixed $entity): bool;

    /**
     * 根据 IDs 批量删除
     *
     * @param array $ids 需要删除的数据主键集合
     * @return bool 是否成功
     */
    public function removeByIds(array $ids): bool;

    /**
     * 批量新增
     *
     * @param array $list 实体集合
     * @return void
     */
    public function saveBatch(array $list): void;

    /**
     * 批量新增（指定批次大小）
     *
     * @param array $list 需要插入的集合数据
     * @param int $batchSize 批次大小
     * @return void
     */
    public function saveBatchWithSize(array $list, int $batchSize): void;

    /**
     * 批量更新
     *
     * @param array $list 集合数据
     * @return void
     */
    public function updateBatch(array $list): void;

    /**
     * ID 设置正序排列
     *
     * @return WarmQuery 查询对象
     */
    public function orderById(): WarmQuery;

    /**
     * 创建时间设置正序排列
     *
     * @return WarmQuery 查询对象
     */
    public function orderByCreateTime(): WarmQuery;

    /**
     * 更新时间设置正序排列
     *
     * @return WarmQuery 查询对象
     */
    public function orderByUpdateTime(): WarmQuery;

    /**
     * 设置字段正序排列
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery 查询对象
     */
    public function orderByAsc(string $orderByField): WarmQuery;

    /**
     * 设置字段倒序排列
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery 查询对象
     */
    public function orderByDesc(string $orderByField): WarmQuery;

    /**
     * 用户自定义排序方案
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery 查询对象
     */
    public function orderBy(string $orderByField): WarmQuery;
}
