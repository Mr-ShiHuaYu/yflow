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

namespace Yflow\core\orm\service\impl;

use support\Model;
use Yflow\core\FlowEngine;
use Yflow\core\orm\agent\WarmQuery;
use Yflow\core\orm\dao\IFlowBaseDao;
use Yflow\core\orm\service\IWarmService;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\page\Page;
use Yflow\core\utils\SqlHelper;

/**
 * WarmServiceImpl - ORM 服务基类实现
 *
 * 所有 Service 实现的抽象基类，提供基础的 CRUD 功能
 * @implements IWarmService<IFlowBaseDao>
 * @template T of IFlowBaseDao
 * @abstract
 */
abstract class WarmServiceImpl implements IWarmService
{

    /**
     * DAO 对象
     * @var T|null $warmDao
     */
    protected ?IFlowBaseDao $warmDao = null;

    /**
     * 获取 DAO 对象
     *
     * @return T DAO 对象
     */
    public function getDao(): IFlowBaseDao
    {
        return $this->warmDao;
    }

    /**
     * 设置 DAO 对象（抽象方法，子类必须实现）
     *
     * @param T $warmDao DAO 对象
     * @return IWarmService
     */
    abstract public function setDao(IFlowBaseDao $warmDao): IWarmService;

    /**
     * 根据 ID 查询
     *
     * @param mixed $id 主键
     * @return Model|null
     */
    public function getById(mixed $id): ?Model
    {
        return $this->getDao()->selectById($id);
    }

    /**
     * 根据 IDs 批量查询
     *
     * @param array $ids 主键集合
     * @return array<Model> 实体列表
     */
    public function getByIds(array $ids): array
    {
        return $this->getDao()->selectByIds($ids);
    }

    /**
     * 分页查询
     *
     * @param mixed $entity 查询实体
     * @param Page $page 分页对象
     * @return Page
     */
    public function page(mixed $entity, Page $page): Page
    {
        return $this->getDao()->selectPage($entity, $page);
    }

    /**
     * 查询列表
     *
     * @param mixed $entity 查询实体
     * @return array
     */
    public function list(mixed $entity): array
    {
        return $this->getDao()->selectList($entity, null);
    }

    /**
     * 查询列表（可排序）
     *
     * @param mixed $entity 查询实体
     * @param WarmQuery|null $query 查询条件（包含排序）
     * @return array
     */
    public function listWithQuery(mixed $entity, ?WarmQuery $query = null): array
    {
        return $this->getDao()->selectList($entity, $query);
    }

    /**
     * 查询单条记录
     *
     * @param mixed $entity 查询实体
     * @return Model|null
     */
    public function getOne(mixed $entity): ?Model
    {
        $list = $this->getDao()->selectList($entity, null);
        return CollUtil::getOne($list);
    }

    /**
     * 获取总数量
     *
     * @param mixed $entity 查询实体
     * @return int
     */
    public function selectCount(mixed $entity): int
    {
        return $this->getDao()->selectCount($entity);
    }

    /**
     * 判断是否存在
     *
     * @param mixed $entity 查询实体
     * @return bool
     */
    public function exists(mixed $entity): bool
    {
        $count = $this->selectCount($entity);
        return $count > 0;
    }

    /**
     * 新增
     *
     * @param mixed $entity 实体对象
     * @return bool
     */
    public function save(mixed $entity): bool
    {
        // 自动填充字段
        $this->insertFill($entity);
        if (method_exists($entity, "toArray")) {
            $entity = $entity->toArray();
        }
        $result = $this->getDao()->insert($entity);
        return SqlHelper::retBool($result);
    }

    /**
     * 根据 ID 修改
     *
     * @param mixed $entity 实体对象
     * @return bool
     */
    public function updateById(mixed $entity): bool
    {
        // 自动填充更新字段
        $this->updateFill($entity);

        $result = $this->getDao()->updateById($entity);
        return SqlHelper::retBool($result);
    }

    /**
     * 根据 ID 删除
     *
     * @param mixed $id 主键
     * @return bool
     */
    public function removeById(mixed $id): bool
    {
        $result = $this->getDao()->deleteById($id);
        return SqlHelper::retBool($result);
    }

    /**
     * 根据实体删除
     *
     * @param mixed $entity 实体对象
     * @return bool
     */
    public function remove(mixed $entity): bool
    {
        if (is_object($entity)) {
            $entityArray = method_exists($entity, 'toArray') ? $entity->toArray() : (array)$entity;
        } else {
            $entityArray = (array)$entity;
        }
        $query = $this->getDao()->query();
        foreach ($entityArray as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }
        $result = $query->delete();
        return SqlHelper::retBool($result);
    }

    /**
     * 根据 IDs 批量删除
     *
     * @param array $ids 需要删除的数据主键集合
     * @return bool
     */
    public function removeByIds(array $ids): bool
    {
        $result = $this->getDao()->deleteByIds($ids);
        return SqlHelper::retBool($result);
    }

    /**
     * 批量新增
     *
     * @param array $list 实体集合
     * @return void
     */
    public function saveBatch(array $list): void
    {
        if (CollUtil::isEmpty($list)) {
            return;
        }
        $this->saveBatchWithSize($list, 1000);
    }

    /**
     * 批量新增（指定批次大小）
     *
     * @param array $list 需要插入的集合数据
     * @param int $batchSize 批次大小
     * @return void
     */
    public function saveBatchWithSize(array $list, int $batchSize): void
    {
        if (CollUtil::isEmpty($list)) {
            return;
        }

        $batchSize = $batchSize > 0 ? $batchSize : 1000;
        $split     = CollUtil::split($list, $batchSize);

        foreach ($split as $ts) {
            // 为每个批次填充字段
            foreach ($ts as $item) {
                $this->insertFill($item);
            }
            // 批量保存
            $this->getDao()->saveBatch($ts);
        }
    }

    /**
     * 批量更新
     *
     * @param array $list 集合数据
     * @return void
     */
    public function updateBatch(array $list): void
    {
        if (CollUtil::isEmpty($list)) {
            return;
        }

        // 填充更新字段
        foreach ($list as $item) {
            $this->updateFill($item);
        }

        $this->getDao()->updateBatch($list);
    }

    /**
     * ID 设置正序排列
     *
     * @return WarmQuery
     */
    public function orderById(): WarmQuery
    {
        return (new WarmQuery($this))->orderById();
    }

    /**
     * 创建时间设置正序排列
     *
     * @return WarmQuery
     */
    public function orderByCreateTime(): WarmQuery
    {
        return (new WarmQuery($this))->orderByCreateTime();
    }

    /**
     * 更新时间设置正序排列
     *
     * @return WarmQuery
     */
    public function orderByUpdateTime(): WarmQuery
    {
        return (new WarmQuery($this))->orderByUpdateTime();
    }

    /**
     * 设置字段正序排列
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery
     */
    public function orderByAsc(string $orderByField): WarmQuery
    {
        return (new WarmQuery($this))->orderByAsc($orderByField);
    }

    /**
     * 设置字段倒序排列
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery
     */
    public function orderByDesc(string $orderByField): WarmQuery
    {
        return (new WarmQuery($this))->orderByDesc($orderByField);
    }

    /**
     * 用户自定义排序方案
     *
     * @param string $orderByField 排序字段
     * @return WarmQuery
     */
    public function orderBy(string $orderByField): WarmQuery
    {
        return (new WarmQuery($this))->orderBy($orderByField);
    }

    /**
     * 插入时自动填充字段
     *
     * @param mixed $entity 实体对象
     * @return void
     */
    protected function insertFill(mixed $entity): void
    {
        if (!is_object($entity)) {
            return;
        }

        $dataFillHandler = FlowEngine::dataFillHandler();
        if ($dataFillHandler === null) {
            return;
        }
        $dataFillHandler->idFill($entity);
        $dataFillHandler->insertFill($entity);
    }

    /**
     * 更新时自动填充字段
     *
     * @param mixed $entity 实体对象
     * @return void
     */
    protected function updateFill(mixed $entity): void
    {
        $dataFillHandler = FlowEngine::dataFillHandler();
        if ($dataFillHandler === null) {
            return;
        }
        $dataFillHandler->updateFill($entity);
    }
}
