<?php

namespace Yflow\ui\service;

use Yflow\core\dto\DefJson;
use Yflow\core\dto\InfoItem;
use Yflow\core\dto\PromptContent;
use Yflow\core\enums\NodeType;
use Yflow\core\utils\MapUtil;

/**
 * ChartExtService 默认实现
 */
trait ChartExtServiceTrait
{
    /**
     * 初始化流程图提示信息
     *
     * @param DefJson $defJson 流程定义json对象
     */
    public function initPromptContent(DefJson $defJson): void
    {
        $defJson->setTopText("流程名称: " . $defJson->getFlowName());
        foreach ($defJson->getNodeList() as $nodeJson) {
            // 提示信息主对象
            $promptContent = new PromptContent();

            if (NodeType::isGateWay($nodeJson->getNodeType())) {
                continue;
            }

            // 设置 dialogStyle 样式
            $promptContent->setDialogStyle(MapUtil::mergeAllObj(
                "position", "absolute",
                "backgroundColor", "#fff",
                "border", "1px solid #ccc",
                "borderRadius", "4px",
                "boxShadow", "0 2px 8px rgba(0, 0, 0, 0.15)",
                "padding", "8px 12px",
                "fontSize", "14px",
                "zIndex", 1000,
                "maxWidth", "500px",
                "color", "#333"
            ));

            // 创建 info 列表
            $infoList = [];

            // 添加第一个条目: 任务名称
            $item = new InfoItem();
            $item->setPrefix("任务名称: ")
                ->setContent($nodeJson->getNodeName())
                ->setContentStyle(MapUtil::mergeAllObj(
                    "border", "1px solid #d1e9ff",
                    "backgroundColor", "#e8f4ff",
                    "padding", "4px 8px",
                    "borderRadius", "4px"
                ))
                ->setRowStyle(MapUtil::mergeAllObj("fontWeight", "bold",
                    "margin", "0 0 6px 0",
                    "padding", "0 0 8px 0",
                    "borderBottom", "1px solid #ccc"
                ));
            $infoList[] = $item;
            $promptContent->setInfo($infoList);

            $nodeJson->setPromptContent($promptContent);
        }
    }
}
