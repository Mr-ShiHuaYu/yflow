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

namespace Yflow\core\orm\dao;

use Illuminate\Database\Eloquent\Model;
use Yflow\core\orm\agent\WarmQuery;
use Yflow\core\utils\page\Page;

/**
 * IFlowBaseDao - BaseMapper 接口
 *
 */
interface IFlowBaseDao
{

    /**
     * 根据 id 查询
     *
     * @param mixed $id 主键
     * @return Model|null 实体
     */
    public function selectById(mixed $id): ?Model;

    /**
     * 根据 ids 批量查询
     *
     * @param array $ids 主键集合
     * @return array<Model> 实体列表
     */
    public function selectByIds(array $ids): array;

    /**
     * 分页查询
     *
     * @param mixed $entity 实体
     * @param Page $page 分页对象
     * @return Page 分页结果
     */
    public function selectPage(mixed $entity, Page $page): Page;

    /**
     * 条件查询列表
     *
     * @param mixed $entity 实体
     * @param WarmQuery $query 查询条件
     * @return array<Model> 实体列表
     */
    public function selectList(mixed $entity, WarmQuery $query): array;

    /**
     * 查询数量
     *
     * @param mixed $entity 实体
     * @return int 数量
     */
    public function selectCount(mixed $entity): int;

    /**
     * 新增
     *
     * @param mixed $entity 实体
     * @return int 影响行数
     */
    // public function save(mixed $entity): int;

    /**
     * 根据 id 修改
     *
     * @param mixed $entity 实体
     * @return int 影响行数
     */
    public function updateById(mixed $entity): int;

    /**
     * 根据 entity 删除
     *
     * @param mixed $entity 实体
     * @return int 影响行数
     */
    // public function delete(mixed $entity): int;

    /**
     * 根据 id 删除
     *
     * @param mixed $id 主键
     * @return int 影响行数
     */
    public function deleteById(mixed $id): int;

    /**
     * 根据 ids 批量删除
     *
     * @param array $ids 需要删除的数据主键集合
     * @return int 影响行数
     */
    public function deleteByIds(array $ids): int;

    /**
     * 批量新增
     *
     * @param array $list 实体列表
     * @return void
     */
    public function saveBatch(array $list): void;

    /**
     * 批量修改
     *
     * @param array $list 实体列表
     * @return void
     */
    public function updateBatch(array $list): void;
}
