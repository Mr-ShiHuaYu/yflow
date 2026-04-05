<?php

namespace Yflow\ui\service;

use Yflow\core\dto\Tree;

/**
 * 分类接口
 *
 *
 * @since 2025/6/24
 */
interface CategoryService
{
    /**
     * 查询分类
     *
     * @return array<Tree> 分类
     */
    public function queryCategory(): array;
}
