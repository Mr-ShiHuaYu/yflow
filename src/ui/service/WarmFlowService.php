<?php

namespace Yflow\ui\service;

use Yflow\core\constant\ExceptionCons;
use Yflow\core\dto\ApiResult;
use Yflow\core\dto\DefJson;
use Yflow\core\dto\FlowDto;
use Yflow\core\dto\FlowParams;
use Yflow\core\enums\FormCustomEnum;
use Yflow\core\enums\ModelEnum;
use Yflow\core\exception\FlowException;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\orm\dao\IFlowFormDao;
use Yflow\core\orm\dao\IFlowInstanceDao;
use Yflow\core\utils\AssertUtil;
use Yflow\core\utils\ExceptionUtil;
use Yflow\core\utils\StreamUtils;
use Yflow\core\utils\StringUtils;
use Yflow\ui\dto\HandlerFeedBackDto;
use Yflow\ui\dto\HandlerQuery;
use Yflow\ui\utils\TreeUtil;
use Yflow\ui\vo\Dict;
use Yflow\ui\vo\HandlerFeedBackVo;
use Yflow\ui\vo\HandlerSelectVo;
use Yflow\ui\vo\NodeExt;
use Yflow\ui\vo\WarmFlowVo;
use Exception;

/**
 * 设计器Controller 可选择是否放行，放行可与业务系统共享权限，主要是用来访问业务系统数据
 *
 *
 */
class WarmFlowService
{
    /**
     * 返回流程定义的配置
     *
     */
    public static function config(): ApiResult
    {
        $warmFlowVo = new WarmFlowVo();
        $warmFlow = FlowEngine::getFlowConfig();
        // 获取tokenName
        $tokenName = $warmFlow->getTokenName();
        if (StringUtils::isEmpty($tokenName)) {
            return ApiResult::fail("未配置tokenName");
        }
        $tokenNames = explode(",", $tokenName);
        $tokenNameList = array_filter($tokenNames, function ($item) {
            return StringUtils::isNotEmpty($item);
        });
        $tokenNameList = array_map('trim', $tokenNameList);
        $warmFlowVo->setTokenNameList($tokenNameList);
        return ApiResult::ok($warmFlowVo);
    }

    /**
     * 保存流程json字符串
     *
     * @param DefJson $defJson 流程数据集合
     * @param bool $onlyNodeSkip 是否只保存节点和跳转
     * @return ApiResult
     * @throws Exception 异常
     * @author xiarg
     * @since 2024/10/29 16:31
     */
    public static function saveJson(DefJson $defJson, bool $onlyNodeSkip): ApiResult
    {
        FlowEngine::defService()->saveDef($defJson, $onlyNodeSkip);
        return ApiResult::ok();
    }

    /**
     * 获取流程定义数据(包含节点和跳转)
     *
     * @param int|null $id 流程定义id
     * @return ApiResult<DefJson>
     * @throws FlowException
     * @author xiarg
     * @since 2024/10/29 16:31
     */
    public static function queryDef(?int $id): ApiResult
    {
        try {
            if ($id === null) {
                $defJson = new DefJson();
                $defJson->setModelValue(ModelEnum::CLASSICS->name)
                    ->setFormCustom(FormCustomEnum::N->name);
            } else {
                $defJson = FlowEngine::defService()->queryDesign($id);
            }
            $categoryService = FrameInvoker::getBean(CategoryService::class);
            if ($categoryService !== null) {
                $treeList = $categoryService->queryCategory();
                $defJson->setCategoryList(TreeUtil::buildTree($treeList));
            }
            $formPathService = FrameInvoker::getBean(FormPathService::class);
            if ($formPathService !== null) {
                $treeList = $formPathService->queryFormPath();
                $defJson->setFormPathList(TreeUtil::buildTree($treeList));
                if ($id === null) {
                    $defJson->setFormCustom(FormCustomEnum::Y->name);
                }
            }
            return ApiResult::ok($defJson);
        } catch (Exception $e) {
            error_log("获取流程json字符串: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("获取流程json字符串失败", $e));
        }
    }

    /**
     * 获取流程图
     *
     * @param int $id 流程实例id
     * @return ApiResult<DefJson>
     * @throws FlowException
     */
    public static function queryFlowChart(int $id): ApiResult
    {
        try {
            $instance = FlowEngine::insService()->getById($id);
            AssertUtil::isNull($instance, ExceptionCons::NOT_FOUNT_INSTANCE);
            $defJsonStr = $instance->getDefJson();
            /** @var DefJson $defJson */
            $defJson = FlowEngine::$jsonConvert->strToBean($defJsonStr, DefJson::class);
            $defJson->setInstance($instance);

            // 获取流程图三原色
            $defJson->setChartStatusColor(FlowEngine::chartService()->getChartRgb($defJson->getModelValue()));
            // 是否显示流程图顶部文字
            $defJson->setTopTextShow(FlowEngine::getFlowConfig()->isTopTextShow());
            // 需要业务系统实现该接口
            $chartExtService = FrameInvoker::getBean(ChartExtService::class);
            if ($chartExtService !== null) {
                $chartExtService->initPromptContent($defJson);
                $chartExtService->execute($defJson);
            }

            return ApiResult::ok($defJson);
        } catch (Exception $e) {
            error_log("获取流程图: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("获取流程图失败", $e));
        }
    }

    /**
     * 办理人权限设置列表tabs页签
     *
     * @return ApiResult<array<string>>
     * @throws FlowException
     */
    public static function handlerType(): ApiResult
    {
        try {
            // 需要业务系统实现该接口
            /**
             * @var HandlerSelectService $handlerSelectService
             */
            $handlerSelectService = FrameInvoker::getBean(HandlerSelectService::class);
            if ($handlerSelectService === null) {
                return ApiResult::ok([]);
            }
            $handlerType = $handlerSelectService->getHandlerType();
            return ApiResult::ok($handlerType);
        } catch (Exception $e) {
            error_log("办理人权限设置列表tabs页签异常: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("办理人权限设置列表tabs页签失败", $e));
        }
    }

    /**
     * 办理人权限设置列表结果
     *
     * @param HandlerQuery $query
     * @return ApiResult<HandlerSelectVo>
     * @throws FlowException
     */
    public static function handlerResult(HandlerQuery $query): ApiResult
    {
        try {
            $handlerSelectService = FrameInvoker::getBean(HandlerSelectService::class);
            if ($handlerSelectService === null) {
                return ApiResult::ok(new HandlerSelectVo());
            }
            $handlerSelectVo = $handlerSelectService->getHandlerSelect($query);
            return ApiResult::ok($handlerSelectVo);
        } catch (Exception $e) {
            error_log("办理人权限设置列表结果异常: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("办理人权限设置列表结果失败", $e));
        }
    }

    /**
     * 办理人权限名称回显
     *
     * @param HandlerFeedBackDto $handlerFeedBackDto
     * @return ApiResult<array<HandlerFeedBackVo>>
     * @throws FlowException
     */
    public static function handlerFeedback(HandlerFeedBackDto $handlerFeedBackDto): ApiResult
    {
        try {
            // 需要业务系统实现该接口
            /**
             * @var HandlerSelectService $handlerSelectService
             */
            $handlerSelectService = FrameInvoker::getBean(HandlerSelectService::class);
            if ($handlerSelectService === null) {
                $handlerFeedBackVos = StreamUtils::toList($handlerFeedBackDto->getStorageIds(), fn($storageId) => new HandlerFeedBackVo($storageId, null));
                return ApiResult::ok($handlerFeedBackVos);
            }
            $handlerFeedBackVos = $handlerSelectService->handlerFeedback($handlerFeedBackDto->getStorageIds() ?? []);
            return ApiResult::ok($handlerFeedBackVos);
        } catch (Exception $e) {
            error_log("办理人权限名称回显: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("办理人权限名称回显", $e));
        }
    }

    /**
     * 办理人选择项
     *
     * @return ApiResult<array<Dict>>
     * @throws FlowException
     */
    public static function handlerDict(): ApiResult
    {
        try {
            // 需要业务系统实现该接口
            $handlerDictService = FrameInvoker::getBean(HandlerDictService::class);
            if ($handlerDictService === null) {
                $dictList = [];
                $dict = new Dict();
                $dict->setLabel("默认表达式");
                $dict->setValue('${handler}');
                $dict1 = new Dict();
                $dict1->setLabel("spel表达式");
                $dict1->setValue("#{@user.evalVar(#handler)}");
                $dict2 = new Dict();
                $dict2->setLabel("其他");
                $dict2->setValue("");
                $dictList[] = $dict;
                $dictList[] = $dict1;
                $dictList[] = $dict2;

                return ApiResult::ok($dictList);
            }
            return ApiResult::ok($handlerDictService->getHandlerDict());
        } catch (Exception $e) {
            error_log("办理人权限设置列表结果异常: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("办理人权限设置列表结果失败", $e));
        }
    }

    /**
     * 已发布表单列表 该接口不需要业务系统实现
     *
     * @return ApiResult<array<IFlowFormDao>>
     * @throws FlowException
     */
    public static function publishedForm(): ApiResult
    {
        try {
            return ApiResult::ok(FlowEngine::formService()->list(FlowEngine::newForm()->setIsPublish(1)));
        } catch (Exception $e) {
            error_log("已发布表单列表异常: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("已发布表单列表异常", $e));
        }
    }

    /**
     * 读取表单内容
     *
     * @param int $id
     * @return ApiResult<string>
     * @throws FlowException
     */
    public static function getFormContent(int $id): ApiResult
    {
        try {
            return ApiResult::ok(FlowEngine::formService()->getById($id)->getFormContent());
        } catch (Exception $e) {
            error_log("获取表单内容字符串: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("获取表单内容字符串失败", $e));
        }
    }

    /**
     * 保存表单内容,该接口不需要系统实现
     *
     * @param FlowDto $flowDto
     * @return ApiResult
     */
    public static function saveFormContent(FlowDto $flowDto): ApiResult
    {
        FlowEngine::formService()->saveContent($flowDto->getId(), $flowDto->getFormContent());
        return ApiResult::ok();
    }

    /**
     * 根据任务id获取待办任务表单及数据
     *
     * @param int $taskId 当前任务id
     * @return ApiResult<FlowDto>
     * @author liangli
     * @date 2024/8/21 17:08
     **/
    public static function load(int $taskId): ApiResult
    {
        $flowParams = FlowParams::build();

        return ApiResult::ok(FlowEngine::taskService()->load($taskId, $flowParams));
    }

    /**
     * 根据任务id获取已办任务表单及数据
     *
     * @param int $hisTaskId
     * @return ApiResult<FlowDto>
     */
    public static function hisLoad(int $hisTaskId): ApiResult
    {
        $flowParams = FlowParams::build();

        return ApiResult::ok(FlowEngine::taskService()->hisLoad($hisTaskId, $flowParams));
    }

    /**
     * 通用表单流程审批接口
     *
     * @param array<string, mixed> $formData
     * @param int $taskId
     * @param string $skipType
     * @param string $message
     * @param string $nodeCode
     * @return ApiResult<IFlowInstanceDao>
     */
    public static function handle(array $formData, int $taskId, string $skipType, string $message, string $nodeCode): ApiResult
    {
        $flowParams = FlowParams::build()
            ->skipType($skipType)
            ->nodeCode($nodeCode)
            ->message($message);

        $flowParams->formData($formData);

        return ApiResult::ok(FlowEngine::taskService()->skip($taskId, $flowParams));
    }

    /**
     * 获取节点扩展属性
     *
     * @return ApiResult<array<NodeExt>>
     * @throws FlowException
     */
    public static function nodeExt(): ApiResult
    {
        try {
            // 需要业务系统实现该接口
            $nodeExtService = FrameInvoker::getBean(NodeExtService::class);
            if ($nodeExtService === null) {
                return ApiResult::ok([]);
            }
            $nodeExts = $nodeExtService->getNodeExt();
            return ApiResult::ok($nodeExts);
        } catch (Exception $e) {
            error_log("获取节点扩展属性: " . $e->getMessage());
            throw new FlowException(ExceptionUtil::handleMsg("获取节点扩展属性失败", $e));
        }
    }
}
