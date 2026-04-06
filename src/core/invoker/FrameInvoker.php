<?php

/*
 *    Copyright 2024-2025, YFlow (974988176@qq.com).
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

namespace Yflow\core\invoker;

use DI\Container;
use DI\ContainerBuilder;
use Exception;
use Yflow\core\FlowEngine;

/**
 * 获取 Bean 调用器
 *
 * @author ysh
 */
class FrameInvoker
{
    /**
     * 单例实例
     */
    private static ?Container $container = null;

    /**
     * 缓存默认依赖配置
     */
    private static ?array $defaultDependence = null;

    /**
     * 缓存用户添加的依赖配置
     */
    private static array $userDependences = [];

    /**
     * 缓存所有指定类型的Bean
     * @var array<string, array<object>>
     */
    private static array $beansCache = [];

    /**
     * 私有构造函数，防止直接实例化
     */
    private function __construct()
    {
    }

    /**
     * 获取单例实例
     *
     * @return Container|null
     * @throws Exception
     */
    public static function getInstance(): ?Container
    {
        if (self::$container === null) {
            $builder = new ContainerBuilder();

            // 读取默认依赖配置，只读取一次
            if (self::$defaultDependence === null) {
                self::$defaultDependence = require __DIR__ . '/../config/DefaultDependence.php';
            }

            // 合并依赖配置，用户配置优先级高于默认配置
            $dependences = array_merge(self::$defaultDependence, self::$userDependences);
            $builder->addDefinitions($dependences);
            $builder->useAutowiring(true);
            $builder->useAttributes(true);
            self::$container = $builder->build();
        }
        return self::$container;
    }

    /**
     * 获取 Bean 实例
     * @template T
     * @param class-string<T> $className 类名
     * @return T|null Bean 实例或 null
     * @throws Exception
     */
    public static function getBean(string $className)
    {
        try {
            return self::getInstance()->get($className);
        } catch (Exception $e) {
            // 上面获取失败了,说明在配置文件中没有注册
            // 下面检查是否是接口类型,如果是,判断容器中是否有这个接口对应的实现,如果有,并且只有一个实现,则返回该实现,如果有多个实现,抛出异常
            if (interface_exists($className)) {
                // 获取容器中所有实现该接口的对象
                $instances  = [];
                $entryNames = self::getInstance()->getKnownEntryNames();
                foreach ($entryNames as $entryName) {
                    try {
                        $instance = self::getInstance()->get($entryName);
                        if ($instance instanceof $className) {
                            $instances[] = $instance;
                        }
                    } catch (Exception) {
                        // 忽略获取失败的情况
                    }
                }

                // 检查实现数量
                $count = count($instances);
                if ($count === 0) {
                    return null;
                } elseif ($count === 1) {
                    return $instances[0];
                } else {
                    return $instances[0];
                }
            }
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public static function setBean(string $beanName, mixed $bean): void
    {
        self::getInstance()->set($beanName, $bean);
    }

    /**
     * 批量添加依赖,可以覆盖默认依赖
     * @param array $beans
     * @return void
     * @throws Exception
     */
    public static function addDependences(array $beans): void
    {
        if (self::$container === null) {
            // 容器未初始化，存储到用户依赖中
            self::$userDependences = array_merge(self::$userDependences, $beans);
        } else {
            // 容器已初始化，直接添加到容器中
            foreach ($beans as $name => $bean) {
                self::setBean($name, $bean);
            }
        }
    }

    /**
     * 获取所有指定类型的Bean
     * 类似于Java中的Spring getBeanNamesForType功能
     *
     * @template T
     * @param class-string<T> $className 类名
     * @return array<object> 所有该类型的Bean实例数组
     * @throws Exception
     */
    public static function getBeansOfType(string $className): array
    {
        // 检查缓存
        if (isset(self::$beansCache[$className])) {
            return self::$beansCache[$className];
        }

        $instances = [];

        try {
            // 先尝试直接获取，看是否是直接注册的Bean
            $instance = self::getInstance()->get($className);
            if ($instance instanceof $className) {
                $instances[] = $instance;
            }
        } catch (Exception $e) {
            // 忽略，直接从容器中查找
        }

        // 遍历容器中所有已注册的Bean，查找实现该接口或继承该类的所有Bean
        $entryNames = self::getInstance()->getKnownEntryNames();
        foreach ($entryNames as $entryName) {
            try {
                $instance = self::getInstance()->get($entryName);
                if ($instance instanceof $className) {
                    // 避免重复添加
                    $alreadyExists = false;
                    foreach ($instances as $existing) {
                        if ($existing === $instance) {
                            $alreadyExists = true;
                            break;
                        }
                    }
                    if (!$alreadyExists) {
                        $instances[] = $instance;
                    }
                }
            } catch (Exception) {
                // 忽略获取失败的情况
            }
        }

        // 缓存结果
        self::$beansCache[$className] = $instances;

        return $instances;
    }

    /**
     * 清除Bean缓存
     * 当容器发生变化时可以调用此方法清除缓存
     *
     * @return void
     */
    public static function clearBeansCache(): void
    {
        self::$beansCache = [];
    }
}
