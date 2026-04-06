<?php

namespace Yflow\ui\vo;

use JsonSerializable;
use Yflow\core\dto\FlowPage;
use Yflow\core\dto\Tree;

/**
 * 流程设计器-办理人选择
 *
 *
 */
class HandlerSelectVo implements JsonSerializable
{
    /**
     * 办理人选择，分页列表，有具体办理对象 比如：部门、角色和用户等情况
     * @var FlowPage<HandlerAuth>|null
     */
    private ?FlowPage $handlerAuths = null;

    /**
     * 左侧树状选择，配合handlerAuths使用，比如用户先选择部门，然后选择用户
     * @var array<Tree>|null
     */
    private ?array $treeSelections = null;

    /**
     * 获取办理人选择
     * @return FlowPage<HandlerAuth>|null
     */
    public function getHandlerAuths(): ?FlowPage
    {
        return $this->handlerAuths;
    }

    /**
     * 设置办理人选择
     * @param FlowPage<HandlerAuth>|null $handlerAuths
     * @return $this
     */
    public function setHandlerAuths(?FlowPage $handlerAuths): self
    {
        $this->handlerAuths = $handlerAuths;
        return $this;
    }

    /**
     * 获取左侧树状选择
     * @return array<Tree>|null
     */
    public function getTreeSelections(): ?array
    {
        return $this->treeSelections;
    }

    /**
     * 设置左侧树状选择
     * @param array<Tree>|null $treeSelections
     * @return $this
     */
    public function setTreeSelections(?array $treeSelections): self
    {
        $this->treeSelections = $treeSelections;
        return $this;
    }

    /**
     * 实现 JsonSerializable 接口
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'handlerAuths'   => $this->handlerAuths,
            'treeSelections' => $this->treeSelections,
        ];
    }
}
