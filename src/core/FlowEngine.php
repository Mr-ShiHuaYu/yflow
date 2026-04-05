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

namespace Yflow\core;

use Closure;
use Yflow\core\config\YFlowConfig;
use Yflow\core\handler\DataFillHandler;
use Yflow\core\handler\PermissionHandler;
use Yflow\core\handler\TenantHandler;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\json\JsonConvert;
use Yflow\core\listener\GlobalListener;
use Yflow\core\orm\service\IWarmService;
use Yflow\core\service\ChartService;
use Yflow\core\service\DefService;
use Yflow\core\service\FormService;
use Yflow\core\service\HisTaskService;
use Yflow\core\service\InsService;
use Yflow\core\service\NodeService;
use Yflow\core\service\SkipService;
use Yflow\core\service\TaskService;
use Yflow\core\service\UserService;
use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowFormModel;
use Yflow\impl\orm\laravel\FlowHisTaskModel;
use Yflow\impl\orm\laravel\FlowInstanceModel;
use Yflow\impl\orm\laravel\FlowNodeModel;
use Yflow\impl\orm\laravel\FlowSkipModel;
use Yflow\impl\orm\laravel\FlowTaskModel;
use Yflow\impl\orm\laravel\FlowUserModel;

/**
 * FlowEngine - 流程引擎
 *
 * 整个流程引擎的核心入口类，提供所有 Service 的访问入口
 * 以及实体对象的创建方法
 *
 *
 */
class FlowEngine
{

    /**
     * Service 实例（静态变量）
     */
    private static ?DefService     $defService     = null;
    private static ?NodeService    $nodeService    = null;
    private static ?SkipService    $skipService    = null;
    private static ?InsService     $insService     = null;
    private static ?TaskService    $taskService    = null;
    private static ?HisTaskService $hisTaskService = null;
    private static ?UserService    $userService    = null;
    private static ?FormService    $formService    = null;
    private static ?ChartService   $chartService   = null;

    /**
     * 实体对象创建者（Supplier）
     */
    private static Closure|null $defSupplier     = null;
    private static Closure|null $nodeSupplier    = null;
    private static Closure|null $skipSupplier    = null;
    private static Closure|null $insSupplier     = null;
    private static Closure|null $taskSupplier    = null;
    private static Closure|null $hisTaskSupplier = null;
    private static Closure|null $userSupplier    = null;
    private static Closure|null $formSupplier    = null;

    /**
     * 配置和处理器
     */
    private static ?YFlowConfig       $flowConfig        = null;
    private static ?DataFillHandler   $dataFillHandler   = null;
    private static ?TenantHandler     $tenantHandler     = null;
    private static ?PermissionHandler $permissionHandler = null;
    private static ?GlobalListener    $globalListener    = null;

    /**
     * JSON 转换器
     */
    public static ?JsonConvert $jsonConvert = null;

    /**
     * 获取 DefService
     * @return DefService|null
     */
    public static function defService(): ?DefService
    {
        return self::getObj(self::$defService, DefService::class);
    }

    /**
     * 获取 NodeService
     * @return NodeService|null
     */
    public static function nodeService(): ?NodeService
    {
        return self::getObj(self::$nodeService, NodeService::class);
    }

    /**
     * 获取 SkipService
     * @return SkipService|null
     */
    public static function skipService(): ?SkipService
    {
        return self::getObj(self::$skipService, SkipService::class);
    }

    /**
     * 获取 InsService
     * @return InsService|null
     */
    public static function insService(): ?InsService
    {
        return self::getObj(self::$insService, InsService::class);
    }

    /**
     * 获取 TaskService
     * @return TaskService|null
     */
    public static function taskService(): ?TaskService
    {
        return self::getObj(self::$taskService, TaskService::class);
    }

    /**
     * 获取 HisTaskService
     * @return HisTaskService|null
     */
    public static function hisTaskService(): ?HisTaskService
    {
        return self::getObj(self::$hisTaskService, HisTaskService::class);
    }

    /**
     * 获取 UserService
     * @return UserService|null
     */
    public static function userService(): ?UserService
    {
        return self::getObj(self::$userService, UserService::class);
    }

    /**
     * 获取 FormService
     * @return FormService|null
     */
    public static function formService(): ?FormService
    {
        return self::getObj(self::$formService, FormService::class);
    }

    /**
     * 获取 ChartService
     * @return ChartService|null
     */
    public static function chartService(): ?ChartService
    {
        return self::getObj(self::$chartService, ChartService::class);
    }

    /**
     * 设置 FlowDefinitionModel 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewDef(callable $supplier): void
    {
        self::$defSupplier = $supplier;
    }

    /**
     * 创建新的 FlowDefinitionModel 对象
     * @return FlowDefinitionModel|null
     */
    public static function newDef(): ?FlowDefinitionModel
    {
        if (self::$defSupplier !== null) {
            return call_user_func(self::$defSupplier);
        }
        return null;
    }

    /**
     * 设置 FlowNodeModel 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewNode(callable $supplier): void
    {
        self::$nodeSupplier = $supplier;
    }

    /**
     * 创建新的 FlowNodeModel 对象
     * @return FlowNodeModel|null
     */
    public static function newNode(): ?FlowNodeModel
    {
        if (self::$nodeSupplier !== null) {
            return call_user_func(self::$nodeSupplier);
        }
        return null;
    }

    /**
     * 设置 Skip 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewSkip(callable $supplier): void
    {
        self::$skipSupplier = $supplier;
    }

    /**
     * 创建新的 Skip 对象
     * @return FlowSkipModel|null
     */
    public static function newSkip(): ?FlowSkipModel
    {
        if (self::$skipSupplier !== null) {
            return call_user_func(self::$skipSupplier);
        }
        return null;
    }

    /**
     * 设置 FlowInstanceModel 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewIns(callable $supplier): void
    {
        self::$insSupplier = $supplier;
    }

    /**
     * 创建新的 FlowInstanceModel 对象
     * @return FlowInstanceModel|null
     */
    public static function newIns(): ?FlowInstanceModel
    {
        if (self::$insSupplier !== null) {
            return call_user_func(self::$insSupplier);
        }
        return null;
    }

    /**
     * 设置 FlowTaskModel 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewTask(callable $supplier): void
    {
        self::$taskSupplier = $supplier;
    }

    /**
     * 创建新的 FlowTaskModel 对象
     * @return FlowTaskModel|null
     */
    public static function newTask(): ?FlowTaskModel
    {
        if (self::$taskSupplier !== null) {
            return call_user_func(self::$taskSupplier);
        }
        return null;
    }

    /**
     * 设置 HisTask 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewHisTask(callable $supplier): void
    {
        self::$hisTaskSupplier = $supplier;
    }

    /**
     * 创建新的 HisTask 对象
     * @return FlowHisTaskModel|null
     */
    public static function newHisTask(): ?FlowHisTaskModel
    {
        if (self::$hisTaskSupplier !== null) {
            return call_user_func(self::$hisTaskSupplier);
        }
        return null;
    }

    /**
     * 设置 User 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewUser(callable $supplier): void
    {
        self::$userSupplier = $supplier;
    }

    /**
     * 创建新的 User 对象
     * @return FlowUserModel|null
     */
    public static function newUser(): ?FlowUserModel
    {
        if (self::$userSupplier !== null) {
            return call_user_func(self::$userSupplier);
        }
        return null;
    }

    /**
     * 设置 FlowFormModel 创建者
     * @param callable $supplier
     * @return void
     */
    public static function setNewForm(callable $supplier): void
    {
        self::$formSupplier = $supplier;
    }

    /**
     * 创建新的 FlowFormModel 对象
     * @return FlowFormModel|null
     */
    public static function newForm(): ?FlowFormModel
    {
        if (self::$formSupplier !== null) {
            return call_user_func(self::$formSupplier);
        }
        return null;
    }

    /**
     * 获取流程配置
     * @return YFlowConfig|null
     */
    public static function getFlowConfig(): ?YFlowConfig
    {
        return self::$flowConfig;
    }

    /**
     * 设置流程配置
     * @param YFlowConfig $flowConfig
     * @return void
     */
    public static function setFlowConfig(YFlowConfig $flowConfig): void
    {
        self::$flowConfig = $flowConfig;
    }

    /**
     * 初始化数据填充处理器
     * @return void
     */
    public static function initDataFillHandler(): void
    {
        self::$dataFillHandler = self::initBean(DataFillHandler::class);
    }

    /**
     * 初始化租户处理器
     * @return void
     */
    public static function initTenantHandler(): void
    {
        self::$tenantHandler = self::initBean(TenantHandler::class);
    }

    /**
     * 初始化权限处理器
     * @return void
     */
    public static function initPermissionHandler(): void
    {
        self::$permissionHandler = self::initBean(PermissionHandler::class);
    }

    /**
     * 初始化全局监听器
     * @return void
     */
    public static function initGlobalListener(): void
    {
        self::$globalListener = self::initBean(GlobalListener::class);
    }

    public static function initJsonConvert(): void
    {
        self::$jsonConvert = self::initBean(JsonConvert::class);
    }

    /**
     * 获取数据填充处理器
     * @return DataFillHandler|null
     */
    public static function dataFillHandler(): ?DataFillHandler
    {
        return self::$dataFillHandler;
    }

    /**
     * 获取权限处理器
     * @return PermissionHandler|null
     */
    public static function permissionHandler(): ?PermissionHandler
    {
        return self::$permissionHandler;
    }

    /**
     * 获取租户处理器
     * @return TenantHandler|null
     */
    public static function tenantHandler(): ?TenantHandler
    {
        return self::$tenantHandler;
    }

    /**
     * 获取全局监听器
     * @return GlobalListener|null
     */
    public static function globalListener(): ?GlobalListener
    {
        return self::$globalListener;
    }

    /**
     * 获取数据库类型
     * @return string|null
     */
    public static function dataSourceType(): ?string
    {
        return self::$flowConfig?->getDataSourceType();
    }

    /**
     * 获取对象（从静态变量或容器中获取）
     *
     * @param mixed $obj 静态变量
     * @param string $className 类名
     * @return mixed|null
     */
    private static function getObj(?IWarmService $obj, string $className): mixed
    {
        if ($obj !== null) {
            return $obj;
        }

        // 尝试从容器中获取
        if (class_exists(FrameInvoker::class)) {
            $obj = FrameInvoker::getBean($className);
        }

        return $obj;
    }

    /**
     * 从容器中初始化 Bean
     *
     * @param string $className Bean 类名
     * @return mixed|null
     */
    private static function initBean(string $className): mixed
    {
        $bean = null;

        // 从容器中获取
        if ($bean === null && class_exists(FrameInvoker::class)) {
            $bean = FrameInvoker::getBean($className);
        }

        return $bean;
    }
}
