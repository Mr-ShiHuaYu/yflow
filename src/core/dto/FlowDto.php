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

namespace Yflow\core\dto;


use Yflow\impl\orm\laravel\FlowFormModel;


/**
 * FlowDto - 流程数据传输对象
 *
 * @author vanlin
 * @since 2024-9-24 11:11
 */
class FlowDto
{

    /**
     * ID
     */
    public ?int $id = null;

    /**
     * 表单内容
     */
    public ?string $formContent = null;

    /**
     * 表单数据
     */
    public ?FlowFormModel $form = null;

    /**
     * 数据
     */
    public mixed $data = null;

    /**
     * 获取 ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * 设置 ID
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * 获取表单内容
     * @return string|null
     */
    public function getFormContent(): ?string
    {
        return $this->formContent;
    }

    /**
     * 设置表单内容
     * @param string|null $formContent
     * @return self
     */
    public function setFormContent(?string $formContent): self
    {
        $this->formContent = $formContent;
        return $this;
    }

    /**
     * 获取表单数据
     * @return FlowFormModel|null
     */
    public function getForm(): ?FlowFormModel
    {
        return $this->form;
    }

    /**
     * 设置表单数据
     * @param FlowFormModel|null $form
     * @return self
     */
    public function setForm(?FlowFormModel $form): self
    {
        $this->form = $form;
        return $this;
    }

    /**
     * 获取数据
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * 设置数据
     * @param mixed $data
     * @return self
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Serialize the object
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            'id'          => $this->id,
            'formContent' => $this->formContent,
            'form'        => $this->form,
            'data'        => $this->data
        ]);
    }

    /**
     * Unserialize the object
     * @param string $data
     * @return void
     */
    public function unserialize(string $data): void
    {
        $unserialized      = unserialize($data);
        $this->id          = $unserialized['id'];
        $this->formContent = $unserialized['formContent'];
        $this->form        = $unserialized['form'];
        $this->data        = $unserialized['data'];
    }
}
