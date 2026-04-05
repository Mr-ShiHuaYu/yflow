<?php

namespace Yflow;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Yflow\core\annotation\Bean;
use Yflow\core\config\YFlowConfig;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\utils\ExpressionUtil;
use Yflow\impl\expression\ConditionStrategyDefault;
use Yflow\impl\expression\ConditionStrategySpel;
use Yflow\impl\expression\HandlerStrategySpel;
use Yflow\impl\expression\ListenerStrategySpel;
use Yflow\impl\expression\VoteSignStrategyDefault;
use Yflow\impl\expression\VoteSignStrategySpel;
use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowFormModel;
use Yflow\impl\orm\laravel\FlowHisTaskModel;
use Yflow\impl\orm\laravel\FlowInstanceModel;
use Yflow\impl\orm\laravel\FlowNodeModel;
use Yflow\impl\orm\laravel\FlowSkipModel;
use Yflow\impl\orm\laravel\FlowTaskModel;
use Yflow\impl\orm\laravel\FlowUserModel;

/**
 * y-flow流程引擎启动类,使用前必须调用 init 方法
 * before 和 after 方法用于在初始化前执行自定义操作,参数为Flow配置对象,可以自定义修改配置,或者注册自定义的Bean等
 */
class YFlowBootstrap
{
    /**
     * 存储初始化前的回调函数
     */
    private static array $beforeCallbacks = [];

    /**
     * 存储初始化后的回调函数
     */
    private static array $afterCallbacks = [];

    /**
     * 注册初始化前的回调函数,可以进行修改默认配置,可以进行注册自定义的Bean等
     */
    public static function registerBeforeCallback(callable $callback): void
    {
        self::$beforeCallbacks[] = $callback;
    }

    /**
     * 注册初始化后的回调函数
     */
    public static function registerAfterCallback(callable $callback): void
    {
        self::$afterCallbacks[] = $callback;
    }

    private static function getBasePath(): string
    {
        $basePath = '';
        if (class_exists(\Phar::class)) {
            $basePath = \Phar::running();
        }
        if (empty($basePath)) {
            $basePath = getcwd();
            while ($basePath !== dirname($basePath)) {
                if (is_dir("$basePath/vendor") && is_file("$basePath/start.php")) {
                    break;
                }
                $basePath = dirname($basePath);
            }
            if ($basePath === dirname($basePath)) {
                $basePath = dirname(__DIR__, 5);
            }
        }
        return realpath($basePath) ?: $basePath;
    }

    public static function init(): YFlowConfig
    {
        self::setNewEntity();
        $warmFlow = new YFlowConfig();

        // 执行初始化前的回调函数
        foreach (self::$beforeCallbacks as $callback) {
            call_user_func($callback, $warmFlow);
        }

        $warmFlow->init();

        FlowEngine::setFlowConfig($warmFlow);
        self::setExpression();
        self::scanAndRegisterBeans($warmFlow->getBeanScanDir()); // 扫描并注册带有 Bean 注解的类到容器

        // 执行初始化后的回调函数
        foreach (self::$afterCallbacks as $callback) {
            call_user_func($callback);
        }

        echo "【y-flow】 加载完成" . PHP_EOL;
        return $warmFlow;
    }

    /**
     * 扫描并注册带有 Bean 注解的类到容器
     * @param array $scanDirectories 扫描bean的目录数组
     * @throws \Exception
     */
    private static function scanAndRegisterBeans(array $scanDirectories = []): void
    {
        $basePath = self::getBasePath();
        foreach ($scanDirectories as $directory) {
            if (!is_dir($directory)) {
                continue;
            }

            // 递归扫描目录
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    // 计算类名
                    $relativePath = str_replace([$basePath, '.php'], ['', ''], $file->getPathname());
                    $className    = str_replace('/', '\\', $relativePath);

                    // 检查类是否存在
                    if (!class_exists($className)) {
                        continue;
                    }

                    // 反射类
                    $reflectionClass = new ReflectionClass($className);

                    // 检查是否有 Bean 注解
                    $attributes = $reflectionClass->getAttributes(Bean::class);
                    foreach ($attributes as $attribute) {
                        // 获取注解实例
                        $beanAnnotation = $attribute->newInstance();

                        // 确定 Bean 名称
                        $beanName = $beanAnnotation->name;
                        if (empty($beanName)) {
                            // 如果没有指定名称，使用完整的类名
                            $beanName = $reflectionClass->getName();
                        }
                        // 注册到容器
                        FrameInvoker::setBean($beanName, function () use ($className) {
                            return new $className();
                        });

                        echo "【y-flow】 注册 Bean: {$beanName} => {$className}" . PHP_EOL;
                    }
                }
            }
        }
    }

    private static function setNewEntity(): void
    {
//        TODO 这里不同的ORM框架需要返回对应自己的 model 实现类
        FlowEngine::setNewDef(function () {
            return new FlowDefinitionModel();
        });
        FlowEngine::setNewIns(function () {
            return new FlowInstanceModel();
        });
        FlowEngine::setNewHisTask(function () {
            return new FlowHisTaskModel();
        });
        FlowEngine::setNewNode(function () {
            return new FlowNodeModel();
        });
        FlowEngine::setNewSkip(function () {
            return new FlowSkipModel();
        });
        FlowEngine::setNewTask(function () {
            return new FlowTaskModel();
        });
        FlowEngine::setNewUser(function () {
            return new FlowUserModel();
        });
        FlowEngine::setNewForm(function () {
            return new FlowFormModel();
        });
    }

    private static function setExpression(): void
    {
        ExpressionUtil::init(); // php中没有静态初始化,必须手动调用
        ExpressionUtil::setExpression(new ConditionStrategyDefault());
        ExpressionUtil::setExpression(new ConditionStrategySpel());
        ExpressionUtil::setExpression(new ListenerStrategySpel());
        ExpressionUtil::setExpression(new HandlerStrategySpel());
        ExpressionUtil::setExpression(new VoteSignStrategyDefault());
        ExpressionUtil::setExpression(new VoteSignStrategySpel());
    }

}
