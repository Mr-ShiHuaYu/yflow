<?php

namespace Yflow\core\handler;

/**
 * 全局租户处理器接口
 *
 *
 */
interface TenantHandler
{
    /**
     * 获取租户 ID
     *
     * @return string|null
     */
    public function getTenantId(): ?string;
}
