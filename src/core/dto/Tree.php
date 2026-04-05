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


/**
 * Tree - 页面树列表
 *
 * @author ruoyi
 */
class Tree
{

    /**
     * ID
     */
    public ?string $id = null;

    /**
     * 名称
     */
    public ?string $name = null;

    /**
     * 父 ID
     */
    public ?string $parentId = null;

    /**
     * 子节点
     */
    public array $children = [];

    /**
     * 构造函数
     *
     * @param string|null $id
     * @param string|null $name
     * @param string|null $parentId
     * @param array $children
     */
    public function __construct(?string $id = null, ?string $name = null, ?string $parentId = null, array $children = [])
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->parentId = $parentId;
        $this->children = $children;
    }

    /**
     * 获取 ID
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * 设置 ID
     * @param string|null $id
     * @return self
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * 获取名称
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * 设置名称
     * @param string|null $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 获取父 ID
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * 设置父 ID
     * @param string|null $parentId
     * @return self
     */
    public function setParentId(?string $parentId): self
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * 获取子节点
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * 设置子节点
     * @param array $children
     * @return self
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;
        return $this;
    }

    /**
     * 添加子节点
     * @param Tree $child
     * @return self
     */
    public function addChild(Tree $child): self
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * Serialize the object
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            'id'       => $this->id,
            'name'     => $this->name,
            'parentId' => $this->parentId,
            'children' => $this->children
        ]);
    }

    /**
     * Unserialize the object
     * @param string $data
     * @return void
     */
    public function unserialize(string $data): void
    {
        $unserialized   = unserialize($data);
        $this->id       = $unserialized['id'];
        $this->name     = $unserialized['name'];
        $this->parentId = $unserialized['parentId'];
        $this->children = $unserialized['children'];
    }
}
