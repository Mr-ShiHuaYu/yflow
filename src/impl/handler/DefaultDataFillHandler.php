<?php

namespace Yflow\impl\handler;

use Yflow\core\FlowEngine;
use Yflow\core\handler\DataFillHandler;
use Yflow\core\utils\IdUtils;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\StringUtils;

/**
 * 数据填充 handler，以下三个接口按照实际情况实现
 *
 *
 * @since 2023/4/1 15:37
 */
class DefaultDataFillHandler implements DataFillHandler
{

    /**
     * ID 填充
     *
     * @param object $entity
     * @return void
     */
    public function idFill(object $entity): void
    {
        if (ObjectUtil::isNull($entity)) {
            return;
        }

        if ($entity->getId() === null) {
            $entity->setId(IdUtils::nextId());
        }
    }

    /**
     * 新增填充
     *
     * @param object $entity
     * @return void
     */
    public function insertFill(object $entity): void
    {
        if (ObjectUtil::isNull($entity)) {
            return;
        }

        // 设置创建时间
        $entity->setCreateTime(
            ObjectUtil::isNotNull($entity->getCreateTime())
                ? $entity->getCreateTime()
                : date('Y-m-d H:i:s')
        );

        // 设置更新时间
        $entity->setUpdateTime(
            ObjectUtil::isNotNull($entity->getUpdateTime())
                ? $entity->getUpdateTime()
                : date('Y-m-d H:i:s')
        );

        // 获取办理人
        $permissionHandler = FlowEngine::permissionHandler();
        $handler           = null;
        if ($permissionHandler !== null) {
            $handler = $permissionHandler->getHandler();
        }

        // 设置创建人和更新人
        if (method_exists($entity, 'setCreateBy') && method_exists($entity, 'getCreateBy')) {
            $entity->setCreateBy(
                StringUtils::isNotEmpty($handler) ? $handler : $entity->getCreateBy()
            );
        }
        if (method_exists($entity, 'setUpdateBy') && method_exists($entity, 'getUpdateBy')) {
            $entity->setUpdateBy(
                StringUtils::isNotEmpty($handler) ? $handler : $entity->getUpdateBy()
            );
        }
    }

    /**
     * 设置更新常用参数
     *
     * @param object $entity
     * @return void
     */
    public function updateFill(object $entity): void
    {
        if (ObjectUtil::isNull($entity)) {
            return;
        }

        // 设置更新时间
        $entity->setUpdateTime(
            ObjectUtil::isNotNull($entity->getUpdateTime())
                ? $entity->getUpdateTime()
                : date('Y-m-d H:i:s')
        );

        // 获取办理人
        $permissionHandler = FlowEngine::permissionHandler();
        $handler           = null;
        if ($permissionHandler !== null) {
            $handler = $permissionHandler->getHandler();
        }

        // 设置更新人
        $entity->setUpdateBy(
            StringUtils::isNotEmpty($handler) ? $handler : $entity->getUpdateBy()
        );
    }
}
