<?php

namespace Yflow\ui\service;

use Yflow\ui\vo\Dict;

/**
 * 流程设计器-获取办理人选择项
 *
 *
 */
interface HandlerDictService
{
    /**
     * 获取办理人选择项
     *
     * @return array<Dict> 结果
     */
    public function getHandlerDict(): array;
}
