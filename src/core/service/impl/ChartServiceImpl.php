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

namespace Yflow\core\service\impl;

use Yflow\core\dto\DefJson;
use Yflow\core\dto\PathWayData;
use Yflow\core\dto\SkipJson;
use Yflow\core\enums\ChartStatus;
use Yflow\core\enums\NodeType;
use Yflow\core\enums\SkipType;
use Yflow\core\FlowEngine;
use Yflow\core\orm\dao\IFlowInstanceDao;
use Yflow\core\orm\dao\IFlowSkipDao;
use Yflow\core\service\ChartService;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\StreamUtils;
use Yflow\core\utils\StringUtils;

/**
 * ChartServiceImpl - 流程图绘制 Service 业务层处理
 *
 *
 * @since 2024-12-30
 */
class ChartServiceImpl implements ChartService
{

    /**
     * 获取流程开启时的流程图元数据
     *
     * @param PathWayData $pathWayData 办理过程中途径数据，用于渲染流程图
     * @return string 流程图元数据 json
     */
    public function startMetadata(PathWayData $pathWayData): string
    {
        $defJson  = FlowEngine::defService()->queryDesign($pathWayData->getDefId());
        $nodeList = $defJson->getNodeList();
        $nodeMap  = StreamUtils::toMap($nodeList, fn($node) => $node->getNodeCode(),
            fn($node) => $node->setStatus(ChartStatus::NOT_DONE));

        $skipMap = [];
        foreach ($nodeList as $node) {
            foreach ($node->getSkipList() as $skip) {
                $key           = $this->getSkipKey($skip);
                $skipMap[$key] = $skip->setStatus(ChartStatus::NOT_DONE);
            }
        }

        foreach ($pathWayData->getPathWayNodes() as $node) {
            if (isset($nodeMap[$node->getNodeCode()])) {
                $nodeMap[$node->getNodeCode()]->setStatus(ChartStatus::DONE);
            }
        }

        foreach ($pathWayData->getPathWaySkips() as $skip) {
            $key = $this->getSkipKey($skip);
            if (isset($skipMap[$key])) {
                $skipMap[$key]->setStatus(ChartStatus::DONE);
            }
        }

        foreach ($pathWayData->getTargetNodes() as $node) {
            if (isset($nodeMap[$node->getNodeCode()])) {
                $nodeMap[$node->getNodeCode()]->setStatus(
                    NodeType::isEnd($node->getNodeType()) ? ChartStatus::DONE : ChartStatus::TO_DO
                );
            }
        }

        return FlowEngine::$jsonConvert->objToStr($defJson);
    }

    /**
     * 获取流程运行时的流程图元数据
     *
     * @param PathWayData $pathWayData 办理过程中途径数据，用于渲染流程图
     * @return string 流程图元数据 json
     */
    public function skipMetadata(PathWayData $pathWayData): string
    {
        /**
         * @var IFlowInstanceDao $instance
         */
        $instance = FlowEngine::insService()->getById($pathWayData->getInsId());
        /**
         * @var DefJson $defJson
         */
        $defJson = FlowEngine::$jsonConvert->strToBean($instance->getDefJson(), DefJson::class);

        $nodeList = $defJson->getNodeList();
        $skipList = StreamUtils::toListAll($defJson->getNodeList(), fn($node) => $node->getSkipList());

        $nodeMap = StreamUtils::toMap($nodeList, fn($node) => $node->getNodeCode(), fn($node) => $node);
        $skipMap = StreamUtils::toMap($skipList, fn($skip) => $this->getSkipKey($skip), fn($skip) => $skip);

        foreach ($pathWayData->getPathWayNodes() as $node) {
            $nodeJson = $nodeMap[$node->getNodeCode()] ?? null;
            if ($nodeJson) {
                if (SkipType::isPass($pathWayData->getSkipType())) {
                    $nodeJson->setStatus(ChartStatus::DONE);
                } elseif (SkipType::isReject($pathWayData->getSkipType())) {
                    $nodeJson->setStatus(ChartStatus::NOT_DONE);
                }
            }
        }

        foreach ($pathWayData->getPathWaySkips() as $skip) {
            $skipJson = $skipMap[$this->getSkipKey($skip)] ?? null;
            if ($skipJson) {
                if (SkipType::isPass($pathWayData->getSkipType())) {
                    $skipJson->setStatus(ChartStatus::DONE);
                } elseif (SkipType::isReject($pathWayData->getSkipType())) {
                    $skipJson->setStatus(ChartStatus::NOT_DONE);
                }
            }
        }

        foreach ($pathWayData->getTargetNodes() as $node) {
            $nodeJson = $nodeMap[$node->getNodeCode()] ?? null;
            if ($nodeJson) {
                if (NodeType::isEnd($node->getNodeType())) {
                    $nodeJson->setStatus(ChartStatus::DONE);
                } else {
                    $nodeJson->setStatus(ChartStatus::TO_DO);
                }
            }
        }

        if (SkipType::isReject($pathWayData->getSkipType())) {
            $skipNextMap = [];
            foreach ($skipList as $skip) {
                if (!SkipType::isReject($skip->getSkipType())) {
                    $skipNextMap[$skip->getNowNodeCode()][] = $skip;
                }
            }
            foreach ($pathWayData->getTargetNodes() as $node) {
                $this->rejectReset($node->getNodeCode(), $skipNextMap, $nodeMap);
            }
        }

        foreach ($pathWayData->getTargetNodes() as $node) {
            if (NodeType::isEnd($node->getNodeType())) {
                foreach ($nodeList as $nodeJson) {
                    if (ChartStatus::isToDo($nodeJson->getStatus())) {
                        $nodeJson->setStatus(ChartStatus::NOT_DONE);
                    }
                }
            }
        }

        return FlowEngine::$jsonConvert->objToStr($defJson);
    }

    /**
     * 获取流程图三原色
     *
     * @param string $modelValue 流程模型
     * @return array 流程图颜色列表
     */
    public function getChartRgb(string $modelValue): array
    {
        $chartStatusColor   = [];
        $done               = ChartStatus::getDone($modelValue);
        $chartStatusColor[] = implode(',', $done);
        $toDo               = ChartStatus::getToDo($modelValue);
        $chartStatusColor[] = implode(',', $toDo);
        $notDone            = ChartStatus::getNotDone($modelValue);
        $chartStatusColor[] = implode(',', $notDone);
        return $chartStatusColor;
    }

    /**
     * 获取跳转键
     *
     * @param IFlowSkipDao|SkipJson $skip 跳转对象
     * @return string 跳转键
     */
    private function getSkipKey(IFlowSkipDao|SkipJson $skip): string
    {
        return StringUtils::join([
            $skip->getNowNodeCode(),
            $skip->getSkipType(),
            $skip->getSkipCondition(),
            $skip->getNextNodeCode()
        ], ":");
    }

    /**
     * 退回重置
     *
     * @param string $nodeCode 节点编码
     * @param array $skipNextMap 跳转下一节点映射
     * @param array $nodeMap 节点映射
     * @return void
     */
    private function rejectReset(string $nodeCode, array $skipNextMap, array $nodeMap): void
    {
        $oneNextSkips = $skipNextMap[$nodeCode] ?? null;
        if (CollUtil::isNotEmpty($oneNextSkips)) {
            foreach ($oneNextSkips as $oneNextSkip) {
                if (ObjectUtil::isNotNull($oneNextSkip) && !ChartStatus::isNotDone($oneNextSkip->getStatus())) {
                    $oneNextSkip->setStatus(ChartStatus::NOT_DONE);
                    $nodeJson = $nodeMap[$oneNextSkip->getNextNodeCode()] ?? null;
                    if (ObjectUtil::isNotNull($nodeJson) && !ChartStatus::isNotDone($nodeJson->getStatus())) {
                        $nodeJson->setStatus(ChartStatus::NOT_DONE);
                        $this->rejectReset($nodeJson->getNodeCode(), $skipNextMap, $nodeMap);
                    }
                }
            }
        }
    }
}
