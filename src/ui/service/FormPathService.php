<?php

namespace Yflow\ui\service;

use Yflow\core\dto\Tree;

/**
 * 自定义表单路径接口
 *
 *
 * @since 2025/10/22
 */
interface FormPathService
{
    /**
     * 查询自定义表单路径
     *
     * @return array<Tree> 自定义表单路径
     */
    public function queryFormPath(): array;
}
