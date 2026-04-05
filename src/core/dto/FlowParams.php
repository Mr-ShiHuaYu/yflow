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

use Yflow\core\FlowEngine;


/**
 * FlowParams - 工作流内置参数
 *
 *
 * @since 2023/3/31 17:18
 */
class FlowParams
{

    /**
     * 流程编码
     */
    private ?string $flowCode = null;

    /**
     * 当前办理人唯一标识：就是确定唯一用的，如用户 id，通常用来入库，记录流程实例创建人，办理人
     */
    private ?string $handler = null;

    /**
     * 节点编码（如果要指定跳转节点，传入）
     */
    private ?string $nodeCode = null;

    /**
     * 用户权限标识：和办理权限有关，是否有办理权限，通俗来说，就是设计器里面预设的办理人，和这个标识是否有交集，有交集就可以办理，审批的时候，就不会提示报错
     */
    private array $permissionFlag = [];

    /**
     * 跳转类型（PASS 审批通过 REJECT 退回）
     */
    private ?string $skipType = null;

    /**
     * @return string|null
     */
    public function getSkipType(): ?string
    {
        return $this->skipType;
    }

    /**
     * @param string|null $skipType
     * @return FlowParams
     */
    public function skipType(?string $skipType): FlowParams
    {
        $this->skipType = $skipType;
        return $this;
    }

    /**
     * 审批意见
     */
    private ?string $message = null;

    /**
     * 流程变量
     */
    private ?array $variable = [];

    /**
     * 流程实例状态
     */
    private ?string $flowStatus = null;

    /**
     * 历史任务表状态
     */
    private ?string $hisStatus = null;

    /**
     * 流程激活状态（0 挂起 1 激活）
     */
    private ?int $activityStatus = null;

    /**
     * 协作方式 (1 审批 2 转办 3 委派 4 会签 5 票签 6 加签 7 减签)
     */
    private ?int $cooperateType = null;

    /**
     * 扩展字段，预留给业务系统使用
     */
    private ?string $ext = null;

    /**
     * 扩展字段，预留给业务系统使用
     */
    private ?string $hisTaskExt = null;

    /**
     * 增加办理人：加签，转办，委托
     */
    private ?array $addHandlers = [];

    /**
     * 减少办理人：减签，委托
     */
    private ?array $reductionHandlers = [];

    /**
     * 忽略 - 办理权限校验（true：忽略，false：不忽略）
     */
    private bool $ignore = false;

    /**
     * 忽略 - 委派处理（true：忽略，false：不忽略）
     */
    private bool $ignoreDepute = false;

    /**
     * 忽略 - 会签票签处理（true：忽略，false：不忽略）
     */
    private bool $ignoreCooperate = false;

    /**
     * 执行的下个任务的办理人
     */
    private ?array $nextHandler = [];

    /**
     * 下个任务处理人配置类型（true-追加，false-覆盖，默认 false）
     */
    private bool $nextHandlerAppend = false;

    /**
     * 构建 FlowParams 实例
     * @return self
     */
    public static function build(): self
    {
        return new self();
    }

    /**
     * 设置流程编码
     * @param string $flowCode
     * @return self
     */
    public function flowCode(string $flowCode): self
    {
        $this->flowCode = $flowCode;
        return $this;
    }

    /**
     * 获取流程编码
     * @return string|null
     */
    public function getFlowCode(): ?string
    {
        return $this->flowCode;
    }

    /**
     * 设置办理人
     * @param string $handler
     * @return self
     */
    public function handler(string $handler): self
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * 获取办理人
     * @return string|null
     */
    public function getHandler(): ?string
    {
        if ($this->handler === null || $this->handler === '') {
            $permissionHandler = FlowEngine::permissionHandler();
            if ($permissionHandler !== null) {
                $this->handler = $permissionHandler->getHandler();
            }
        }
        return $this->handler;
    }

    /**
     * 设置节点编码
     * @param string $nodeCode
     * @return self
     */
    public function nodeCode(string $nodeCode): self
    {
        $this->nodeCode = $nodeCode;
        return $this;
    }

    /**
     * 获取节点编码
     * @return string|null
     */
    public function getNodeCode(): ?string
    {
        return $this->nodeCode;
    }

    /**
     * 设置权限标识
     * @param array $permissionFlag
     * @return self
     */
    public function permissionFlag(array $permissionFlag): self
    {
        $this->permissionFlag = $permissionFlag;
        return $this;
    }

    /**
     * 获取权限标识
     * @return array
     */
    public function getPermissionFlag(): array
    {
        if (count($this->permissionFlag) === 0) {
            $permissionHandler = FlowEngine::permissionHandler();
            if ($permissionHandler !== null) {
                $this->permissionFlag = $permissionHandler->permissions();
            }
        }
        return $this->permissionFlag;
    }

    /**
     * 设置审批意见
     * @param string $message
     * @return self
     */
    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 获取审批意见
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * 设置流程变量
     * @param array|null $variable
     * @return self
     */
    public function variable(?array $variable): self
    {
        $this->variable = $variable;
        return $this;
    }

    /**
     * 获取流程变量
     * @return array|null
     */
    public function getVariable(): ?array
    {
        return $this->variable;
    }

    /**
     * 获取流程变量字符串
     * @return string|null
     */
    public function getVariableStr(): ?string
    {
        return FlowEngine::$jsonConvert->objToStr($this->variable);
    }

    /**
     * 设置流程状态
     * @param string $flowStatus
     * @return self
     */
    public function flowStatus(string $flowStatus): self
    {
        $this->flowStatus = $flowStatus;
        return $this;
    }

    /**
     * 获取流程状态
     * @return string|null
     */
    public function getFlowStatus(): ?string
    {
        return $this->flowStatus;
    }

    /**
     * 设置历史任务状态
     * @param string $hisStatus
     * @return self
     */
    public function hisStatus(string $hisStatus): self
    {
        $this->hisStatus = $hisStatus;
        return $this;
    }

    /**
     * 获取历史任务状态
     * @return string|null
     */
    public function getHisStatus(): ?string
    {
        return $this->hisStatus;
    }

    /**
     * 设置激活状态
     * @param int|null $activityStatus
     * @return self
     */
    public function activityStatus(?int $activityStatus): self
    {
        $this->activityStatus = $activityStatus;
        return $this;
    }

    /**
     * 获取激活状态
     * @return int|null
     */
    public function getActivityStatus(): ?int
    {
        return $this->activityStatus;
    }

    /**
     * 设置协作类型
     * @param int|null $cooperateType
     * @return self
     */
    public function cooperateType(?int $cooperateType): self
    {
        $this->cooperateType = $cooperateType;
        return $this;
    }

    /**
     * 获取协作类型
     * @return int|null
     */
    public function getCooperateType(): ?int
    {
        return $this->cooperateType;
    }

    /**
     * 设置扩展字段
     * @param string|null $ext
     * @return self
     */
    public function ext(?string $ext): self
    {
        $this->ext = $ext;
        return $this;
    }

    /**
     * 获取扩展字段
     * @return string|null
     */
    public function getExt(): ?string
    {
        return $this->ext;
    }

    /**
     * 设置历史任务扩展字段
     * @param string|null $hisTaskExt
     * @return self
     */
    public function hisTaskExt(?string $hisTaskExt): self
    {
        $this->hisTaskExt = $hisTaskExt;
        return $this;
    }

    /**
     * 获取历史任务扩展字段
     * @return string|null
     */
    public function getHisTaskExt(): ?string
    {
        return $this->hisTaskExt;
    }

    /**
     * 设置下一个办理人（可变参数）
     * @param string ...$nextHandler
     * @return self
     */
    public function nextHandler(string ...$nextHandler): self
    {
        $this->nextHandler = $nextHandler;
        return $this;
    }

    /**
     * 获取下一个办理人
     * @return array
     */
    public function getNextHandler(): array
    {
        return $this->nextHandler ?? [];
    }

    /**
     * 设置是否追加下一个办理人
     * @param bool $nextHandlerAppend
     * @return self
     */
    public function nextHandlerAppend(bool $nextHandlerAppend): self
    {
        $this->nextHandlerAppend = $nextHandlerAppend;
        return $this;
    }

    /**
     * 获取是否追加下一个办理人
     * @return bool
     */
    public function isNextHandlerAppend(): bool
    {
        return $this->nextHandlerAppend;
    }

    /**
     * 设置增加办理人
     * @param array|null $addHandlers
     * @return self
     */
    public function addHandlers(?array $addHandlers): self
    {
        $this->addHandlers = $addHandlers;
        return $this;
    }

    /**
     * 获取增加办理人
     * @return array|null
     */
    public function getAddHandlers(): ?array
    {
        return $this->addHandlers;
    }

    /**
     * 设置减少办理人
     * @param array|null $reductionHandlers
     * @return self
     */
    public function reductionHandlers(?array $reductionHandlers): self
    {
        $this->reductionHandlers = $reductionHandlers;
        return $this;
    }

    /**
     * 获取减少办理人
     * @return array|null
     */
    public function getReductionHandlers(): ?array
    {
        return $this->reductionHandlers;
    }

    /**
     * 设置是否忽略权限校验
     * @param bool $ignore
     * @return self
     */
    public function ignore(bool $ignore): self
    {
        $this->ignore = $ignore;
        return $this;
    }

    /**
     * 获取是否忽略权限校验
     * @return bool
     */
    public function isIgnore(): bool
    {
        return $this->ignore;
    }

    /**
     * 设置是否忽略委派处理
     * @param bool $ignoreDepute
     * @return self
     */
    public function ignoreDepute(bool $ignoreDepute): self
    {
        $this->ignoreDepute = $ignoreDepute;
        return $this;
    }

    /**
     * 获取是否忽略委派处理
     * @return bool
     */
    public function isIgnoreDepute(): bool
    {
        return $this->ignoreDepute;
    }

    /**
     * 设置是否忽略协作处理
     * @param bool $ignoreCooperate
     * @return self
     */
    public function ignoreCooperate(bool $ignoreCooperate): self
    {
        $this->ignoreCooperate = $ignoreCooperate;
        return $this;
    }

    /**
     * 获取是否忽略协作处理
     * @return bool
     */
    public function isIgnoreCooperate(): bool
    {
        return $this->ignoreCooperate;
    }

    /**
     * 设置表单数据
     * @param array $formData
     * @return self
     */
    public function formData(array $formData): self
    {
        if ($this->variable === null) {
            $this->variable = [];
        }
        $this->variable['formData'] = $formData;
        return $this;
    }

    /**
     * Serialize the object
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            'flowCode'          => $this->flowCode,
            'handler'           => $this->handler,
            'nodeCode'          => $this->nodeCode,
            'permissionFlag'    => $this->permissionFlag,
            'skipType'          => $this->skipType,
            'message'           => $this->message,
            'variable'          => $this->variable,
            'flowStatus'        => $this->flowStatus,
            'hisStatus'         => $this->hisStatus,
            'activityStatus'    => $this->activityStatus,
            'cooperateType'     => $this->cooperateType,
            'ext'               => $this->ext,
            'hisTaskExt'        => $this->hisTaskExt,
            'addHandlers'       => $this->addHandlers,
            'reductionHandlers' => $this->reductionHandlers,
            'ignore'            => $this->ignore,
            'ignoreDepute'      => $this->ignoreDepute,
            'ignoreCooperate'   => $this->ignoreCooperate,
            'nextHandler'       => $this->nextHandler,
            'nextHandlerAppend' => $this->nextHandlerAppend
        ]);
    }

    /**
     * Unserialize the object
     * @param string $data
     * @return void
     */
    public function unserialize(string $data): void
    {
        $unserialized            = unserialize($data);
        $this->flowCode          = $unserialized['flowCode'];
        $this->handler           = $unserialized['handler'];
        $this->nodeCode          = $unserialized['nodeCode'];
        $this->permissionFlag    = $unserialized['permissionFlag'];
        $this->skipType          = $unserialized['skipType'];
        $this->message           = $unserialized['message'];
        $this->variable          = $unserialized['variable'];
        $this->flowStatus        = $unserialized['flowStatus'];
        $this->hisStatus         = $unserialized['hisStatus'];
        $this->activityStatus    = $unserialized['activityStatus'];
        $this->cooperateType     = $unserialized['cooperateType'];
        $this->ext               = $unserialized['ext'];
        $this->hisTaskExt        = $unserialized['hisTaskExt'];
        $this->addHandlers       = $unserialized['addHandlers'];
        $this->reductionHandlers = $unserialized['reductionHandlers'];
        $this->ignore            = $unserialized['ignore'];
        $this->ignoreDepute      = $unserialized['ignoreDepute'];
        $this->ignoreCooperate   = $unserialized['ignoreCooperate'];
        $this->nextHandler       = $unserialized['nextHandler'];
        $this->nextHandlerAppend = $unserialized['nextHandlerAppend'];
    }
}
