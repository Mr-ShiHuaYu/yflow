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

namespace Yflow\core\service;

use Yflow\core\orm\dao\IFlowFormDao;
use Yflow\core\orm\service\IWarmService;
use Yflow\core\utils\page\Page;

/**
 * FormService - 流程表单 Service 接口
 *
 * @author vanlin
 * @since 2024/8/19 10:06
 */
interface FormService extends IWarmService
{

    /**
     * 保存流程表单
     *
     * @param mixed $entity form
     * @return bool 保存情况
     */
    public function save(mixed $entity): bool;

    /**
     * 发布流程表单
     *
     * @param int $id id
     * @return bool 发布结果
     */
    public function publish(int $id): bool;

    /**
     * 取消发布流程表单
     *
     * @param int $id id
     * @return bool 取消发布
     */
    public function unPublish(int $id): bool;

    /**
     * 复制流程表单
     *
     * @param int $id id
     * @return bool 复制表单结果
     */
    public function copyForm(int $id): bool;

    /**
     * 读取流程表单
     *
     * @param string $formCode 表单编码
     * @param string $formVersion 版本
     * @return IFlowFormDao|null 表单信息
     */
    public function getByCode(string $formCode, string $formVersion): ?IFlowFormDao;

    /**
     * 已发布表单
     *
     * @param string|null $formName 表单名
     * @param int $pageNum 页码
     * @param int $pageSize 每页记录
     * @return Page 已发布记录
     */
    public function publishedPage(?string $formName, int $pageNum, int $pageSize): Page;

    /**
     * 保存表单内容
     *
     * @param int $id id
     * @param string $formContent 表单内容
     * @return bool 保存结果
     */
    public function saveContent(int $id, string $formContent): bool;
}
