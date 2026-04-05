<?php

namespace Yflow\ui\service;


use Yflow\core\dto\FlowPage;
use Yflow\core\dto\Tree;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\HttpStatus;
use Yflow\core\utils\MapUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\ui\dto\HandlerFunDto;
use Yflow\ui\dto\HandlerQuery;
use Yflow\ui\dto\TreeFunDto;
use Yflow\ui\utils\TreeUtil;
use Yflow\ui\vo\HandlerAuth;
use Yflow\ui\vo\HandlerFeedBackVo;
use Yflow\ui\vo\HandlerSelectVo;

/**
 * HandlerSelectService 默认实现
 */
trait HandlerSelectServiceTrait
{
    /**
     * 办理人权限名称回显，兼容老项目，新项目重写提高性能
     * 通过的,但性能不好,建议结合业务系统自己重写,参考 @param array<string> $storageIds 入库主键集合
     * @return array<HandlerFeedBackVo> 结果
     *@see \plugin\yflow\app\service\HandlerSelectServiceImpl::handlerFeedbackBak 方法
     */
    public function handlerFeedback(array $storageIds): array
    {
        $handlerFeedBackVos = [];
        if (CollUtil::isEmpty($storageIds)) {
            return $handlerFeedBackVos;
        }
        $authMap = [];
        $handlerTypes = $this->getHandlerType();
        if (CollUtil::isEmpty($handlerTypes)) {
            return $handlerFeedBackVos;
        }
        foreach ($handlerTypes as $handlerType) {
            $handlerQuery = new HandlerQuery();
            $handlerQuery->setHandlerType($handlerType);
            $handlerSelectVo = $this->getHandlerSelect($handlerQuery);
            if (ObjectUtil::isNotNull($handlerSelectVo)) {
                $handlerAuths = $handlerSelectVo->getHandlerAuths();
                $rows = $handlerAuths->getRows();
                if (CollUtil::isNotEmpty($rows)) {
                    foreach ($rows as $row) {
                        $authMap[$row->getStorageId()] = $row->getHandlerName();
                    }
                }
            }
        }

        // 遍历storageIds，按照原本的顺序回显名称
        foreach ($storageIds as $storageId) {
            $handlerFeedBackVos[] = new HandlerFeedBackVo($storageId,
                MapUtil::isEmpty($authMap) ? "" : $authMap[$storageId] ?? "");
        }
        return $handlerFeedBackVos;
    }

    /**
     * 获取办理人选择VO
     *
     * @param HandlerFunDto $handlerFunDto
     * @return HandlerSelectVo
     */
    public function getHandlerSelectVo(HandlerFunDto $handlerFunDto): HandlerSelectVo
    {
        $handlerAuths = [];
        // 遍历角色数据，封装为组件可识别的数据
        foreach ($handlerFunDto->getList() as $obj) {
            $handlerAuth = new HandlerAuth();
            if ($handlerFunDto->getStorageId() !== null) {
                $handlerAuth->setStorageId($handlerFunDto->getStorageId()($obj));
            }
            if ($handlerFunDto->getHandlerCode() !== null) {
                $handlerAuth->setHandlerCode($handlerFunDto->getHandlerCode()($obj));
            }
            if ($handlerFunDto->getHandlerName() !== null) {
                $handlerAuth->setHandlerName($handlerFunDto->getHandlerName()($obj));
            }
            if ($handlerFunDto->getCreateTime() !== null) {
                $handlerAuth->setCreateTime($handlerFunDto->getCreateTime()($obj));
            }
            if ($handlerFunDto->getGroupName() !== null) {
                $handlerAuth->setGroupName($handlerFunDto->getGroupName()($obj));
            }
            $handlerAuths[] = $handlerAuth;
        }
        return $this->getResult($handlerAuths, $handlerFunDto->getTotal());
    }

    /**
     * 获取办理人选择VO（带树结构）
     *
     * @param HandlerFunDto $handlerFunDto
     * @param TreeFunDto $treeFunDto
     * @return HandlerSelectVo
     */
    public function getHandlerSelectVoWithTree(HandlerFunDto $handlerFunDto, TreeFunDto $treeFunDto): HandlerSelectVo
    {
        $handlerSelectVo = $this->getHandlerSelectVo($handlerFunDto);

        $treeList = [];
        foreach ($treeFunDto->getList() as $org) {
            $tree = new Tree();
            if ($treeFunDto->getId() !== null) {
                $tree->setId($treeFunDto->getId()($org));
            }
            if ($treeFunDto->getName() !== null) {
                $tree->setName($treeFunDto->getName()($org));
            }
            if ($treeFunDto->getParentId() !== null) {
                $tree->setParentId($treeFunDto->getParentId()($org));
            }
            $treeList[] = $tree;
        }

        // 通过递归，构建树状结构
        return $handlerSelectVo->setTreeSelections(TreeUtil::buildTree($treeList));
    }

    /**
     * 获取结果
     *
     * @param array<HandlerAuth> $handlerAuths
     * @param int $total
     * @return HandlerSelectVo
     */
    public function getResult(array $handlerAuths, int $total): HandlerSelectVo
    {
        return (new HandlerSelectVo())->setHandlerAuths((new FlowPage())
            ->setCode(HttpStatus::SUCCESS)
            ->setMsg("查询成功")
            ->setRows($handlerAuths)
            ->setTotal($total));
    }
}
