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

use Illuminate\Database\Eloquent\Model;
use Yflow\core\constant\ExceptionCons;
use Yflow\core\enums\PublishStatus;
use Yflow\core\exception\FlowException;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\orm\dao\IFlowFormDao;
use Yflow\core\orm\service\impl\WarmServiceImpl;
use Yflow\core\service\FormService;
use Yflow\core\utils\AssertUtil;
use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\page\Page;

/**
 * FormServiceImpl - 流程表单 Service 业务层处理
 *
 * @author vanlin
 * @since 2024/8/19 10:07
 */
class FormServiceImpl extends WarmServiceImpl implements FormService
{
    public function __construct()
    {
        $this->setDao(FrameInvoker::getBean(IFlowFormDao::class));
    }

    /**
     * 设置 DAO
     *
     * @param IFlowFormDao $warmDao DAO
     * @return FormService
     */
    public function setDao($warmDao): FormService
    {
        $this->warmDao = $warmDao;
        return $this;
    }

    /**
     * 发布流程表单
     *
     * @param int $id id
     * @return bool 发布结果
     * @throws FlowException
     */
    public function publish(int $id): bool
    {
        /**
         * @var IFlowFormDao $form
         */
        $form = $this->getById($id);
        AssertUtil::isTrue($form->getIsPublish() === PublishStatus::PUBLISHED->value, ExceptionCons::FORM_ALREADY_PUBLISH);
        $form->setIsPublish(PublishStatus::PUBLISHED->value);
        return $this->updateById($form);
    }

    /**
     * 取消发布流程表单
     *
     * @param int $id id
     * @return bool 取消发布
     * @throws FlowException
     */
    public function unPublish(int $id): bool
    {
        /**
         * @var IFlowFormDao $form
         */
        $form  = $this->getById($id);
        $nodes = FlowEngine::nodeService()->list(FlowEngine::newNode()->setFormPath("" . $form->getId()));
        AssertUtil::isNotEmpty($nodes, ExceptionCons::EXIST_USE_FORM);
        $definitions = FlowEngine::defService()->list(FlowEngine::newDef()->setFormPath("" . $form->getId()));
        AssertUtil::isNotEmpty($definitions, ExceptionCons::EXIST_USE_FORM);
        AssertUtil::isTrue($form->getIsPublish() === PublishStatus::UNPUBLISHED->value, ExceptionCons::FORM_ALREADY_UN_PUBLISH);
        $form->setIsPublish(PublishStatus::UNPUBLISHED->value);
        return $this->updateById($form);
    }

    /**
     * 保存流程表单
     *
     * @param mixed $entity form
     * @return bool 保存情况
     */
    public function save(mixed $entity): bool
    {
        $entity->setVersion($this->getNewVersion($entity));
        return parent::save($entity);
    }

    /**
     * 复制流程表单
     *
     * @param int $id id
     * @return bool 复制表单结果
     * @throws FlowException
     */
    public function copyForm(int $id): bool
    {
        /**
         * @var IFlowFormDao $form
         */
        $form = clone $this->getById($id);
        AssertUtil::isTrue(ObjectUtil::isNull($form), ExceptionCons::NOT_FOUNT_DEF);
        FlowEngine::dataFillHandler()->idFill($form->setId(null));
        $form->setVersion($this->getNewVersion($form))
            ->setIsPublish(PublishStatus::UNPUBLISHED->value)
            ->setCreateTime(null)
            ->setUpdateTime(null);
        return $this->save($form);
    }

    /**
     * 读取流程表单
     *
     * @param string $formCode 表单编码
     * @param string $formVersion 版本
     * @return IFlowFormDao|null 表单信息
     * @throws FlowException
     */
    public function getByCode(string $formCode, string $formVersion): ?IFlowFormDao
    {
        $list = $this->list(FlowEngine::newForm()->setFormCode($formCode)->setVersion($formVersion));
        AssertUtil::isTrue(CollUtil::isEmpty($list), ExceptionCons::NOT_FOUNT_TASK);
        AssertUtil::isTrue(count($list) > 1, ExceptionCons::FORM_NOT_ONE);
        return $list[0];
    }

    /**
     * 根据 ID 获取表单
     *
     * @param int $id id
     * @return Model 表单信息
     * @throws FlowException
     */
    public function getById(mixed $id): Model
    {
        AssertUtil::isNull($id, ExceptionCons::ID_EMPTY);
        return parent::getById($id);
    }

    /**
     * 已发布表单分页
     *
     * @param string|null $formName 表单名
     * @param int $pageNum 页码
     * @param int $pageSize 每页记录
     * @return Page<IFlowFormDao> 已发布记录
     */
    public function publishedPage(?string $formName, int $pageNum, int $pageSize): Page
    {
        return $this->page(FlowEngine::newForm()->setFormName($formName)->setIsPublish(1),
            Page::pageOf($pageNum, $pageSize));
    }

    /**
     * 保存表单内容
     *
     * @param int $id id
     * @param string $formContent 表单内容
     * @return bool 保存结果
     * @throws FlowException
     */
    public function saveContent(int $id, string $formContent): bool
    {
        /**
         * @var IFlowFormDao $form
         */
        $form = $this->getById($id);
        AssertUtil::isTrue($form->getIsPublish() === PublishStatus::PUBLISHED->value, ExceptionCons::FORM_ALREADY_PUBLISH);

        $form->setFormContent($formContent);
        return $this->updateById($form);
    }

    /**
     * 获取新版本号
     *
     * @param IFlowFormDao $form 表单
     * @return string 版本号
     */
    private function getNewVersion(IFlowFormDao $form): string
    {
        $formCodeList = [$form->getFormCode()];
        /**
         * @var IFlowFormDao $dao
         */
        $dao            = $this->getDao();
        $forms          = $dao->queryByCodeList($formCodeList);
        $highestVersion = 0;

        foreach ($forms as $otherForm) {
            if ($form->getFormCode() === $otherForm->getFormCode()) {
                $version = intval($otherForm->getVersion());
                if ($version > $highestVersion) {
                    $highestVersion = $version;
                }
            }
        }

        $version = "1";
        if ($highestVersion > 0) {
            $version = strval($highestVersion + 1);
        }

        return $version;
    }
}
