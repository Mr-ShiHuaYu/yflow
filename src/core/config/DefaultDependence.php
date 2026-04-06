<?php

use Yflow\core\handler\DataFillHandler;
use Yflow\core\orm\dao\IFlowDefinitionDao;
use Yflow\core\orm\dao\IFlowFormDao;
use Yflow\core\orm\dao\IFlowHisTaskDao;
use Yflow\core\orm\dao\IFlowInstanceDao;
use Yflow\core\orm\dao\IFlowNodeDao;
use Yflow\core\orm\dao\IFlowSkipDao;
use Yflow\core\orm\dao\IFlowTaskDao;
use Yflow\core\orm\dao\IFlowUserDao;
use Yflow\impl\handler\DefaultDataFillHandler;
use Yflow\impl\json\JsonConvertImpl;
use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowFormModel;
use Yflow\impl\orm\laravel\FlowHisTaskModel;
use Yflow\impl\orm\laravel\FlowInstanceModel;
use Yflow\impl\orm\laravel\FlowNodeModel;
use Yflow\impl\orm\laravel\FlowSkipModel;
use Yflow\impl\orm\laravel\FlowTaskModel;
use Yflow\impl\orm\laravel\FlowUserModel;

return [

    //dao注入
    IFlowDefinitionDao::class                => DI\autowire(FlowDefinitionModel::class),
    IFlowFormDao::class                      => DI\autowire(FlowFormModel::class),
    IFlowHisTaskDao::class                   => DI\autowire(FlowHisTaskModel::class),
    IFlowInstanceDao::class                  => DI\autowire(FlowInstanceModel::class),
    IFlowNodeDao::class                      => DI\autowire(FlowNodeModel::class),
    IFlowSkipDao::class                      => DI\autowire(FlowSkipModel::class),
    IFlowTaskDao::class                      => DI\autowire(FlowTaskModel::class),
    IFlowUserDao::class                      => DI\autowire(FlowUserModel::class),


    //service注入
    Yflow\core\service\ChartService::class   => DI\autowire(Yflow\core\service\impl\ChartServiceImpl::class),
    Yflow\core\service\DefService::class     => DI\autowire(Yflow\core\service\impl\DefServiceImpl::class),
    Yflow\core\service\FormService::class    => DI\autowire(Yflow\core\service\impl\FormServiceImpl::class),
    Yflow\core\service\HisTaskService::class => DI\autowire(Yflow\core\service\impl\HisTaskServiceImpl::class),
    Yflow\core\service\InsService::class     => DI\autowire(Yflow\core\service\impl\InsServiceImpl::class),
    Yflow\core\service\NodeService::class    => DI\autowire(Yflow\core\service\impl\NodeServiceImpl::class),
    Yflow\core\service\SkipService::class    => DI\autowire(Yflow\core\service\impl\SkipServiceImpl::class),
    Yflow\core\service\TaskService::class    => DI\autowire(Yflow\core\service\impl\TaskServiceImpl::class),
    Yflow\core\service\UserService::class    => DI\autowire(Yflow\core\service\impl\UserServiceImpl::class),

    // JSON序列化工具
    Yflow\core\json\JsonConvert::class       => DI\autowire(JsonConvertImpl::class),

    //    数据填充
    DataFillHandler::class                   => DI\autowire(DefaultDataFillHandler::class),
    // 办理人权限处理器
];
