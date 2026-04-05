<?php

return [

    //dao注入
    \Yflow\core\orm\dao\IFlowDefinitionDao::class => DI\autowire(\Yflow\impl\orm\laravel\FlowDefinitionModel::class),
    \Yflow\core\orm\dao\IFlowFormDao::class       => DI\autowire(\Yflow\impl\orm\laravel\FlowFormModel::class),
    \Yflow\core\orm\dao\IFlowHisTaskDao::class    => DI\autowire(\Yflow\impl\orm\laravel\FlowHisTaskModel::class),
    \Yflow\core\orm\dao\IFlowInstanceDao::class   => DI\autowire(\Yflow\impl\orm\laravel\FlowInstanceModel::class),
    \Yflow\core\orm\dao\IFlowNodeDao::class       => DI\autowire(\Yflow\impl\orm\laravel\FlowNodeModel::class),
    \Yflow\core\orm\dao\IFlowSkipDao::class       => DI\autowire(\Yflow\impl\orm\laravel\FlowSkipModel::class),
    \Yflow\core\orm\dao\IFlowTaskDao::class       => DI\autowire(\Yflow\impl\orm\laravel\FlowTaskModel::class),
    \Yflow\core\orm\dao\IFlowUserDao::class       => DI\autowire(\Yflow\impl\orm\laravel\FlowUserModel::class),


    //service注入
    Yflow\core\service\ChartService::class        => DI\autowire(Yflow\core\service\impl\ChartServiceImpl::class),
    Yflow\core\service\DefService::class          => DI\autowire(Yflow\core\service\impl\DefServiceImpl::class),
    Yflow\core\service\FormService::class         => DI\autowire(Yflow\core\service\impl\FormServiceImpl::class),
    Yflow\core\service\HisTaskService::class      => DI\autowire(Yflow\core\service\impl\HisTaskServiceImpl::class),
    Yflow\core\service\InsService::class          => DI\autowire(Yflow\core\service\impl\InsServiceImpl::class),
    Yflow\core\service\NodeService::class         => DI\autowire(Yflow\core\service\impl\NodeServiceImpl::class),
    Yflow\core\service\SkipService::class         => DI\autowire(Yflow\core\service\impl\SkipServiceImpl::class),
    Yflow\core\service\TaskService::class         => DI\autowire(Yflow\core\service\impl\TaskServiceImpl::class),
    Yflow\core\service\UserService::class         => DI\autowire(Yflow\core\service\impl\UserServiceImpl::class),

    // JSON序列化工具
    Yflow\core\json\JsonConvert::class            => DI\autowire(\Yflow\impl\json\JsonConvertImpl::class),

    //    数据填充
    \Yflow\core\handler\DataFillHandler::class    => DI\autowire(\Yflow\impl\handler\DefaultDataFillHandler::class),
    // 办理人权限处理器
];
