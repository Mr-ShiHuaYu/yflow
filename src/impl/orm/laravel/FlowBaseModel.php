<?php

namespace Yflow\impl\orm\laravel;

use Yflow\core\orm\agent\WarmQuery;
use Yflow\core\orm\dao\IFlowBaseDao;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\page\Page;
use Illuminate\Database\Eloquent\Model;

class FlowBaseModel extends Model implements IFlowBaseDao
{

    public function selectById(mixed $id): ?Model
    {
        return $this->find($id);
    }

    public function selectByIds(array $ids): array
    {
        return $this->whereIn($this->primaryKey, $ids)->get()->all();
    }

    public function selectPage(mixed $entity, Page $page): Page
    {
        $query = $this->query();
        $this->buildQuery($query, $entity);

        // 添加排序逻辑
        $orderBy = $page->getOrderBy();
        $isAsc   = $page->getIsAsc();
        if (!empty($orderBy)) {
            $query->orderBy($orderBy, $isAsc);
        }

        $total   = $query->count();
        $records = $query->offset(($page->getPageNum() - 1) * $page->getPageSize())
            ->limit($page->getPageSize())
            ->get()
            ->all();

        $page->setTotal($total);
        $page->setList($records);

        return $page;
    }

    public function selectList(mixed $entity, ?WarmQuery $query = null): array
    {
        $builder = $this->query();
        $this->buildQuery($builder, $entity);
        if (ObjectUtil::isNotNull($query)) {
            if (!empty($query->getOrderBy())) {
                $builder->orderBy($query->getOrderBy(), $query->getIsAsc());
            }
        }

        return $builder->get()->all();
    }

    public function selectCount(mixed $entity): int
    {
        $query = $this->query();
        $this->buildQuery($query, $entity);
        return $query->count();
    }

    public function updateById(mixed $entity): int
    {
        if (is_object($entity) && method_exists($entity, 'toArray')) {
            $data = $entity->toArray();
        } else {
            $data = (array)$entity;
        }

        $id = $data[$this->primaryKey] ?? null;
        if (!$id) {
            return 0;
        }

        unset($data[$this->primaryKey]);
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function deleteById(mixed $id): int
    {
        return $this->destroy($id) ? 1 : 0;
    }

    public function deleteByIds(array $ids): int
    {
        return $this->destroy($ids);
    }

    public function saveBatch(array $list): void
    {
        foreach ($list as $item) {
            if (is_object($item) && method_exists($item, 'save')) {
                $item->save();
            } else {
                $this->create((array)$item);
            }
        }
    }

    public function updateBatch(array $list): void
    {
        foreach ($list as $item) {
            $this->updateById($item);
        }
    }

    /**
     * 构建查询条件
     * @param mixed $query
     * @param mixed $entity
     */
    private function buildQuery(mixed $query, mixed $entity): void
    {
        if (is_object($entity)) {
            $entityArray = method_exists($entity, 'toArray') ? $entity->toArray() : (array)$entity;
        } else {
            $entityArray = (array)$entity;
        }

        foreach ($entityArray as $key => $value) {
            if ($value !== null) {
                $query->where($key, $value);
            }
        }
    }
}
