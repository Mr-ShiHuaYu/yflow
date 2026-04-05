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

namespace Yflow\core\config;

use Composer\InstalledVersions;
use Yflow\core\enums\ChartStatus;
use Yflow\core\FlowEngine;

/**
 * YFlowConfig - YFlowConfig 属性配置文件
 *
 */
class YFlowConfig
{

    /**
     * 开关
     * @var bool
     */
    private bool $enabled = true;

    /**
     * 启动 banner
     * @var bool
     */
    private bool $banner = true;

    /**
     * id 生成器类型，不填默认为 orm 扩展自带生成器或者 y-flow 内置的 19 位雪花算法，SnowId14:14 位，SnowId15:15 位，SnowFlake19:19 位
     * @var string|null
     */
    private ?string $keyType = null;

    /**
     * 是否开启逻辑删除
     * @var bool
     */
    private bool $logicDelete = false;

    /**
     * 逻辑删除字段值
     * @var string
     */
    private string $logicDeleteValue = "2";

    /**
     * 逻辑未删除字段
     * @var string
     */
    private string $logicNotDeleteValue = "0";

    /**
     * 数据填充处理类路径
     * @var string|null
     */
    private ?string $dataFillHandlerPath = null;

    /**
     * 租户模式处理类路径
     * @var string|null
     */
    private ?string $tenantHandlerPath = null;

    /**
     * 数据源类型，mybatis 模块对 orm 进一步的封装，由于各数据库分页语句存在差异，
     * 当配置此参数时，以此参数结果为基准，未配置时，取 DataSource 中数据源类型，
     * 兜底为 mysql 数据库
     * @var string|null
     */
    private ?string $dataSourceType = null;

    /**
     * ui 开关
     * @var bool
     */
    private bool $ui = true;

    /**
     * 如果需要工作流共享业务系统权限，默认 Authorization，如果有多个 token，用逗号分隔
     * @var string
     */
    private string $tokenName = "Authorization";

    /**
     * 公共模型流程状态对应的三原色
     * @var array|null
     */
    private ?array $chartStatusColor = null;

    /**
     * 经典模式流程状态对应的三原色
     * @var array|null
     */
    private ?array $chartStatusColorClassics = null;

    /**
     * 仿钉钉模式流程状态对应的三原色
     * @var array|null
     */
    private ?array $chartStatusColorMimic = null;

    /**
     * 是否显示流程图顶部文字
     * @var bool
     */
    private bool $topTextShow = true;


    /**
     * @var array 扫描bean的目录数组
     */
    private array $beanScanDir = [];

    // Getter 方法

    /**
     * 获取 enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * 获取 banner
     *
     * @return bool
     */
    public function isBanner(): bool
    {
        return $this->banner;
    }

    /**
     * 获取 keyType
     *
     * @return string|null
     */
    public function getKeyType(): ?string
    {
        return $this->keyType;
    }

    /**
     * 获取 logicDelete
     *
     * @return bool
     */
    public function isLogicDelete(): bool
    {
        return $this->logicDelete;
    }

    /**
     * 获取 logicDeleteValue
     *
     * @return string
     */
    public function getLogicDeleteValue(): string
    {
        return $this->logicDeleteValue;
    }

    /**
     * 获取 logicNotDeleteValue
     *
     * @return string
     */
    public function getLogicNotDeleteValue(): string
    {
        return $this->logicNotDeleteValue;
    }

    /**
     * 获取 dataFillHandlerPath
     *
     * @return string|null
     */
    public function getDataFillHandlerPath(): ?string
    {
        return $this->dataFillHandlerPath;
    }

    /**
     * 获取 tenantHandlerPath
     *
     * @return string|null
     */
    public function getTenantHandlerPath(): ?string
    {
        return $this->tenantHandlerPath;
    }

    /**
     * 获取 dataSourceType
     *
     * @return string|null
     */
    public function getDataSourceType(): ?string
    {
        return $this->dataSourceType;
    }

    /**
     * 获取 ui
     *
     * @return bool
     */
    public function isUi(): bool
    {
        return $this->ui;
    }

    /**
     * 获取 tokenName
     *
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
     * 获取 chartStatusColor
     *
     * @return array|null
     */
    public function getChartStatusColor(): ?array
    {
        return $this->chartStatusColor;
    }

    /**
     * 获取 chartStatusColorClassics
     *
     * @return array|null
     */
    public function getChartStatusColorClassics(): ?array
    {
        return $this->chartStatusColorClassics;
    }

    /**
     * 获取 chartStatusColorMimic
     *
     * @return array|null
     */
    public function getChartStatusColorMimic(): ?array
    {
        return $this->chartStatusColorMimic;
    }

    /**
     * 获取 topTextShow
     *
     * @return bool
     */
    public function isTopTextShow(): bool
    {
        return $this->topTextShow;
    }


    public function getBeanScanDir(): array
    {
        return $this->beanScanDir;
    }

    // Setter 方法

    /**
     * 设置 enabled
     *
     * @param bool $enabled
     * @return self
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * 设置 banner
     *
     * @param bool $banner
     * @return self
     */
    public function setBanner(bool $banner): self
    {
        $this->banner = $banner;
        return $this;
    }

    /**
     * 设置 keyType
     *
     * @param string|null $keyType
     * @return self
     */
    public function setKeyType(?string $keyType): self
    {
        $this->keyType = $keyType;
        return $this;
    }

    /**
     * 设置 logicDelete
     *
     * @param bool $logicDelete
     * @return self
     */
    public function setLogicDelete(bool $logicDelete): self
    {
        $this->logicDelete = $logicDelete;
        return $this;
    }

    /**
     * 设置 logicDeleteValue
     *
     * @param string $logicDeleteValue
     * @return self
     */
    public function setLogicDeleteValue(string $logicDeleteValue): self
    {
        $this->logicDeleteValue = $logicDeleteValue;
        return $this;
    }

    /**
     * 设置 logicNotDeleteValue
     *
     * @param string $logicNotDeleteValue
     * @return self
     */
    public function setLogicNotDeleteValue(string $logicNotDeleteValue): self
    {
        $this->logicNotDeleteValue = $logicNotDeleteValue;
        return $this;
    }

    /**
     * 设置 dataFillHandlerPath
     *
     * @param string|null $dataFillHandlerPath
     * @return self
     */
    public function setDataFillHandlerPath(?string $dataFillHandlerPath): self
    {
        $this->dataFillHandlerPath = $dataFillHandlerPath;
        return $this;
    }

    /**
     * 设置 tenantHandlerPath
     *
     * @param string|null $tenantHandlerPath
     * @return self
     */
    public function setTenantHandlerPath(?string $tenantHandlerPath): self
    {
        $this->tenantHandlerPath = $tenantHandlerPath;
        return $this;
    }

    /**
     * 设置 dataSourceType
     *
     * @param string|null $dataSourceType
     * @return self
     */
    public function setDataSourceType(?string $dataSourceType): self
    {
        $this->dataSourceType = $dataSourceType;
        return $this;
    }

    /**
     * 设置 ui
     *
     * @param bool $ui
     * @return self
     */
    public function setUi(bool $ui): self
    {
        $this->ui = $ui;
        return $this;
    }

    /**
     * 设置 tokenName
     *
     * @param string $tokenName
     * @return self
     */
    public function setTokenName(string $tokenName): self
    {
        $this->tokenName = $tokenName;
        return $this;
    }

    /**
     * 设置 chartStatusColor
     *
     * @param array|null $chartStatusColor
     * @return self
     */
    public function setChartStatusColor(?array $chartStatusColor): self
    {
        $this->chartStatusColor = $chartStatusColor;
        return $this;
    }

    /**
     * 设置 chartStatusColorClassics
     *
     * @param array|null $chartStatusColorClassics
     * @return self
     */
    public function setChartStatusColorClassics(?array $chartStatusColorClassics): self
    {
        $this->chartStatusColorClassics = $chartStatusColorClassics;
        return $this;
    }

    /**
     * 设置 chartStatusColorMimic
     *
     * @param array|null $chartStatusColorMimic
     * @return self
     */
    public function setChartStatusColorMimic(?array $chartStatusColorMimic): self
    {
        $this->chartStatusColorMimic = $chartStatusColorMimic;
        return $this;
    }

    /**
     * 设置 topTextShow
     *
     * @param bool $topTextShow
     * @return self
     */
    public function setTopTextShow(bool $topTextShow): self
    {
        $this->topTextShow = $topTextShow;
        return $this;
    }


    /**
     * 设置扫描bean的目录数组
     * @param array $beanScanDir
     * @return $this
     */
    public function setBeanScanDir(array $beanScanDir): YFlowConfig
    {
        $this->beanScanDir = $beanScanDir;
        return $this;
    }


    /**
     * 初始化配置
     *
     * @return void
     */
    public function init(): void
    {
        // 设置租户模式
        FlowEngine::initTenantHandler();

        // 设置数据填充处理类
        FlowEngine::initDataFillHandler();

        // 设置办理人权限处理类
        FlowEngine::initPermissionHandler();

        // 设置全局监听器
        FlowEngine::initGlobalListener();

        FlowEngine::initJsonConvert();

        // 打印 banner 图
        $this->printBanner();

        // 初始化流程状态对应的自定义三原色
        ChartStatus::initCustomColor(
            $this->getChartStatusColor(),
            $this->getChartStatusColorClassics(),
            $this->getChartStatusColorMimic()
        );
    }


    /**
     * 打印 Banner
     *
     * @return void
     */
    private function printBanner(): void
    {
        if ($this->isBanner()) {
            echo "
▄▄▄    ▄▄▄           ▄▄▄▄▄▄▄▄  ▄▄▄▄
 ██▄  ▄██            ██▀▀▀▀▀▀  ▀▀██
  ██▄▄██             ██          ██       ▄████▄  ██      ██
   ▀██▀              ███████     ██      ██▀  ▀██ ▀█  ██  █▀
    ██      █████    ██          ██      ██    ██  ██▄██▄██
    ██               ██          ██▄▄▄   ▀██▄▄██▀  ▀██  ██▀
    ▀▀               ▀▀           ▀▀▀▀     ▀▀▀▀     ▀▀  ▀▀
【y-flow】 Version (" . $this->getImplementationVersion() . ")\n";
        }
    }

    /**
     * 获取实现版本
     *
     * @return string
     */
    private function getImplementationVersion(): string
    {
        if (InstalledVersions::isInstalled('webman/database')) {
            return InstalledVersions::getPrettyVersion('webman/database');
        }
        return '未知版本';
    }

}
