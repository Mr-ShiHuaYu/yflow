<?php

namespace Yflow\core\handler;

/**
 * 数据填充 handler，以下三个接口按照实际情况实现
 *
 *
 * @since 2023/4/1 15:37
 */
interface DataFillHandler
{
    /**
     * ID 填充
     *
     * @param object $entity
     * @return void
     */
    public function idFill(object $entity): void;

    /**
     * 新增填充
     *
     * @param object $entity
     * @return void
     */
    public function insertFill(object $entity): void;

    /**
     * 设置更新常用参数
     *
     * @param object $entity
     * @return void
     */
    public function updateFill(object $entity): void;
}
