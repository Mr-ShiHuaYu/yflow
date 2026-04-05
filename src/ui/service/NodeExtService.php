<?php

namespace Yflow\ui\service;

use Yflow\ui\vo\NodeExt;

/**
 * 流程设计器-节点扩展属性
 *
 *
 */
interface NodeExtService
{
    /**
     * 获取节点扩展属性
     *
     * @return array<NodeExt> 结果
     */
    public function getNodeExt(): array;
}
