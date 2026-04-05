<?php

namespace Yflow\ui\service;

use Yflow\core\dto\DefJson;

/**
 * 流程图提示信息
 *
 *
 */
interface ChartExtService
{
    /**
     * 设置流程图提示信息
     *
     * @param DefJson $defJson 流程定义json对象
     */
    public function execute(DefJson $defJson): void;

    /**
     * 初始化流程图提示信息
     *
     * @param DefJson $defJson 流程定义json对象
     */
    public function initPromptContent(DefJson $defJson): void;
}
