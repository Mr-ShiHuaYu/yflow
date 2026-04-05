<?php

namespace Yflow\ui\service;

use Yflow\ui\dto\HandlerFunDto;
use Yflow\ui\dto\HandlerQuery;
use Yflow\ui\dto\TreeFunDto;
use Yflow\ui\vo\HandlerAuth;
use Yflow\ui\vo\HandlerFeedBackVo;
use Yflow\ui\vo\HandlerSelectVo;

/**
 * 流程设计器-获取办理人权限设置列表接口
 *
 *
 */
interface HandlerSelectService
{
    /**
     * 获取办理人权限设置列表tabs页签，如：用户、角色和部门等，可以返回其中一种或者多种，按业务需求决定
     *
     * @return array<string> tabs页签
     */
    public function getHandlerType(): array;

    /**
     * 获取用户列表、角色列表、部门列表等，可以返回其中一种或者多种，按业务需求决定
     *
     * @param HandlerQuery $query 查询参数
     * @return HandlerSelectVo 结果
     */
    public function getHandlerSelect(HandlerQuery $query): HandlerSelectVo;

    /**
     * 办理人权限名称回显，兼容老项目，新项目重写提高性能
     *
     * @param array<string> $storageIds 入库主键集合
     * @return array<HandlerFeedBackVo> 结果
     */
    public function handlerFeedback(array $storageIds): array;

    /**
     * 获取办理人选择VO
     *
     * @param HandlerFunDto $handlerFunDto
     * @return HandlerSelectVo
     */
    public function getHandlerSelectVo(HandlerFunDto $handlerFunDto): HandlerSelectVo;

    /**
     * 获取办理人选择VO（带树结构）
     *
     * @param HandlerFunDto $handlerFunDto
     * @param TreeFunDto $treeFunDto
     * @return HandlerSelectVo
     */
    public function getHandlerSelectVoWithTree(HandlerFunDto $handlerFunDto, TreeFunDto $treeFunDto): HandlerSelectVo;

    /**
     * 获取结果
     *
     * @param array<HandlerAuth> $handlerAuths
     * @param int $total
     * @return HandlerSelectVo
     */
    public function getResult(array $handlerAuths, int $total): HandlerSelectVo;
}
