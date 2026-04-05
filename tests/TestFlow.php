<?php

namespace Tests;
require_once __DIR__ . '/../vendor/autoload.php';

use Exception;
use PHPUnit\Framework\TestCase;
use Yflow\core\dto\FlowParams;
use Yflow\core\enums\FlowStatus;
use Yflow\core\FlowEngine;
use Yflow\core\invoker\FrameInvoker;
use Yflow\core\service\DefService;
use Yflow\core\service\InsService;
use Yflow\core\service\NodeService;
use Yflow\core\service\TaskService;
use Yflow\core\utils\page\Page;
use Yflow\impl\orm\laravel\FlowDefinitionModel;
use Yflow\impl\orm\laravel\FlowFormModel;
use Yflow\impl\orm\laravel\FlowHisTaskModel;
use Yflow\impl\orm\laravel\FlowInstanceModel;
use Yflow\impl\orm\laravel\FlowNodeModel;
use Yflow\impl\orm\laravel\FlowSkipModel;
use Yflow\impl\orm\laravel\FlowTaskModel;
use Yflow\impl\orm\laravel\FlowUserModel;
use Yflow\YFlowBootstrap;

class TestFlow extends TestCase
{
    private DefService  $defService;
    private InsService  $insService;
    private TaskService $taskService;
    private NodeService $nodeService;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        // 初始化流程引擎
        YFlowBootstrap::init();
    }

    private function getDefId(): int
    {
        $flowCodeList = [$this->getFlowCode()];
        $defs         = $this->defService->queryByCodeList($flowCodeList);
        return empty($defs) ? 0 : $defs[0]['id'];
    }

    private function getFlowCode(): string
    {
        return "serial55";
//        return "ysh-test";
    }


    private function getBusinessId(): string
    {
        return "3";
    }

    private function getUser(): FlowParams
    {
        return FlowParams::build()->flowCode($this->getFlowCode())
            ->handler('1')
            ->skipType('PASS');
    }


    private function getInsId(): int
    {
        $list = $this->insService->list(null);
        return collect($list)->last()['id'] ?? throw new Exception("流程实例不存在");
    }

    public function testInit()
    {
        dump('test init');
        $this->assertTrue(true);
    }

    public static function testTruncate(): void
    {
        FlowDefinitionModel::truncate();
        FlowNodeModel::truncate();
        FlowSkipModel::truncate();
        FlowInstanceModel::truncate();
        FlowTaskModel::truncate();
        FlowUserModel::truncate();
        FlowFormModel::truncate();
        FlowHisTaskModel::truncate();
    }

    public function testAll()
    {
        $this->testTruncate();
        $this->testDeployFlow1();
        $this->testPublish2();
        $this->testUnPublish3();
        $this->testPublish2();
        $this->testUnActive5();
        $this->testActive4();
        $this->testQueryDesign6();
        $this->testRemoveDef7();
        $this->testDeployFlow1();
        $this->testPublish2();
        $this->testStartFlow8();
        $this->testRemoveIns9();
        $this->testStartFlow8();
        $this->testUnActiveIns11();
        $this->testActiveIns10();
        $this->testTermination13();
        $this->testStartFlow8();

        $this->testSkipFlow12();
        $this->testSkipAnyNode14();

        $this->testAddSignature();
        $this->testReductionSignature();

        $this->testTransfer();
        $this->testDepute();
        $this->testPage();


        $this->testGetNextNodeList();
        $this->testGetFirstBetweenNode();
        $this->testPreviousNodeList();

        $this->testTruncate();
    }

    /**
     * 部署流程
     */
    public function testDeployFlow1()
    {
        $json_path = __DIR__ . '/../app/flow/resources/leaveFlow-serial-内部测试用.json';
        $json      = file_get_contents($json_path);

        $def = $this->defService->importJson($json);
        $this->assertNotNull($def);
    }

    /**
     * 发布流程
     */
    public function testPublish2()
    {
        $defId  = $this->getDefId();
        $result = $this->defService->publish($defId);
        $this->assertTrue($result);
    }

    /**
     * 取消流程
     */
    public function testUnPublish3()
    {
        $defId  = $this->getDefId();
        $result = $this->defService->unPublish($defId);
        $this->assertTrue($result);
    }

    /**
     * 激活流程
     */
    public function testActive4()
    {
        $defId  = $this->getDefId();
        $result = $this->defService->active($defId);
        $this->assertTrue($result);
    }

    /**
     * 挂起流程
     */
    public function testUnActive5()
    {
        $defId  = $this->getDefId();
        $result = $this->defService->unActive($defId);
        $this->assertTrue($result);
    }

    /**
     * 获取流程定义
     */
    public function testQueryDesign6()
    {
        $defId  = $this->getDefId();
        $design = $this->defService->queryDesign($defId);
        $this->assertNotNull($design);
        dump("获取流程定义：", $design);
    }

    /**
     * 删除流程定义
     */
    public function testRemoveDef7()
    {
        $defId  = $this->getDefId();
        $result = $this->defService->removeDef([$defId]);
        $this->assertTrue($result);
    }

    public function testStartFlow8()
    {
        $businessId = $this->getBusinessId();
        $flowParams = $this->getUser();
        $instance   = $this->insService->start($businessId, $flowParams);
        $this->assertNotNull($instance);
        dump("已开启的流程实例id：" . $instance['id']);

        $tasks = $this->taskService->list(FlowEngine::newTask()->setInstanceId($instance->getId()));
        foreach ($tasks as $task) {
            dump("流转后任务id实例：" . $task['id']);
        }
        $this->assertNotNull($tasks);
    }


    public function testRemoveIns9()
    {
        $result = $this->insService->removeWithTasks([$this->getInsId()]);
        dump("删除流程实例结果：", $result);
        $this->assertTrue($result);
    }

    public function testActiveIns10()
    {
        $insId  = $this->getInsId();
        $result = $this->insService->active($insId);
        $this->assertTrue($result);
    }

    public function testUnActiveIns11()
    {
        $insId  = $this->getInsId();
        $result = $this->insService->unActive($insId);
        $this->assertTrue($result);
    }

    public function testSkipFlow12()
    {
        $insId = $this->getInsId();
        $user  = $this->getUser();
        $user->permissionFlag(['role:1', 'role:2', 'warmFlowInitiator']);
        $user->variable([
            'testLeave' => [],
            'flag'      => '1'
        ]);
        $instance = $this->taskService->skipByInsId($insId, $user);
        $this->assertNotNull($instance);
        dump("流转后流程实例：" . $instance['id']);

        $tasks = $this->taskService->list(['instance_id' => $instance['id']]);
        if (empty($tasks)) {
            dump("流程已完成!");
            return;
        }
        foreach ($tasks as $task) {
            dump("流转后任务id：" . $task['id']);
        }
        $this->assertNotNull($tasks);
    }

    public function testTermination13()
    {
        $taskId     = $this->getTaskId();
        $flowParams = FlowParams::build()
            ->message('终止流程')
            ->handler('1')
            ->permissionFlag(['role:1', 'role:2', 'warmFlowInitiator']);
        $instance   = $this->taskService->termination($taskId, $flowParams);
        $this->assertNotNull($instance);
        dump("流转后流程实例状态：" . $instance['flow_status'] . ' ' . FlowStatus::getValueByKey($instance['flow_status']));
    }

    /**
     * 跳转到指定节点 跳转到结束节点
     */
    public function testSkipAnyNode14()
    {
        // OK
        $taskId = $this->getTaskId();
        $user   = $this->getUser();
        $user->skipType('PASS');
        $user->permissionFlag(['role:1', 'role:2', 'warmFlowInitiator']);
        $user->nodeCode('5');
        $instance = $this->taskService->skip($taskId, $user);
        $this->assertNotNull($instance);
    }

    /**
     * 分页
     */
    public function testPage()
    {
        // ok
        $flowDefinition = [];
        $page           = new Page();
        $page->setPageNum(1);
        $page->setPageSize(1);
        $result = $this->defService->page($flowDefinition, $page);
        $this->assertNotNull($result);
    }

    //    转办
    public function testTransfer()
    {
        // OK
        $taskId     = $this->getTaskId();
        $flowParams = FlowParams::build()
            ->handler('1')
            ->permissionFlag(['role:1', 'role:2', 'user:1', 'warmFlowInitiator'])
            ->addHandlers(['1', '2'])
            ->message('转办');
        $result     = $this->taskService->transfer($taskId, $flowParams);
        $this->assertTrue($result);
    }

    private function getTaskId(): int
    {
        $list = $this->taskService->list(null);
        return collect($list)->last()['id'] ?? throw new Exception("任务不存在");
    }

    // 委派
    public function testDepute()
    {
        // OK
        $taskId     = $this->getTaskId();
        $flowParams = FlowParams::build()
            ->handler('A')
            ->permissionFlag(['role:1', 'role:2', 'user:1', '1'])
            ->addHandlers(['2', '3'])
            ->message('委派');
        $result     = $this->taskService->depute($taskId, $flowParams);
        $this->assertTrue($result);
    }

    // 加签
    public function testAddSignature()
    {
        // ok
        $taskId     = $this->getTaskId();
        $flowParams = FlowParams::build()
            ->handler('1')
            ->permissionFlag(['role:1', 'role:2', 'user:1', '2', 'warmFlowInitiator'])
            ->addHandlers(['1', '2'])
            ->message('加签');
        $result     = $this->taskService->addSignature($taskId, $flowParams);
        $this->assertTrue($result);
    }

    /**
     * 减签
     */
    public function testReductionSignature()
    {
        $taskId     = $this->getTaskId();
        $flowParams = FlowParams::build()
            ->handler('1')
            ->permissionFlag(['role:1', 'role:2', 'user:1', '2', 'warmFlowInitiator'])
            ->reductionHandlers(['1', '2'])
            ->message('减签');
        $result     = $this->taskService->reductionSignature($taskId, $flowParams);
        $this->assertTrue($result);
    }

    /**
     * 获取下面的节点
     */
    public function testGetNextNodeList()
    {
//        OK
        $definitionId = $this->getDefId();
        $nowNodeCode  = '2';
        $nextNodeCode = '';
        $skipType     = 'PASS';
        $variable     = null;
        $nextNodeList = $this->nodeService->getNextNodeList($definitionId, $nowNodeCode, $nextNodeCode, $skipType, $variable);
        $this->assertNotNull($nextNodeList);
        dump("下面的节点：");
        foreach ($nextNodeList as $node) {
            dump($node->getNodeName());
        }
    }

    /**
     * 获取前置和后置的节点
     */
    public function testPreviousNodeList()
    {
//        OK
        $definitionId     = $this->getDefId();
        $nowNodeCode      = '2';
        $previousNodeList = $this->nodeService->previousNodeListByDefId($definitionId, $nowNodeCode);
        $this->assertNotNull($previousNodeList);
        $suffixNodeList = $this->nodeService->suffixNodeListByDefId($definitionId, $nowNodeCode);
        $this->assertNotNull($suffixNodeList);

        dump("所有的前置节点：", array_map(function ($node) {
            return $node->getNodeName();
        }, $previousNodeList));

        dump("所有的后置节点：", array_map(function ($node) {
            return $node->getNodeName();
        }, $suffixNodeList));
    }

    /**
     * 根据流程定义id和流程变量获取第一个中间节点
     * @return void
     */
    public function testGetFirstBetweenNode()
    {
        // ok
        $definitionId = $this->getDefId();
        $nextNodeList = $this->nodeService->getFirstBetweenNode($definitionId, null);
        $this->assertNotNull($nextNodeList);
        dump(array_map(function ($node) {
            return $node->getNodeName();
        }, $nextNodeList));
    }


    protected function setUp(): void
    {
        parent::setUp();
        // 初始化服务
        $this->defService  = FrameInvoker::getBean(DefService::class);
        $this->insService  = FrameInvoker::getBean(InsService::class);
        $this->taskService = FrameInvoker::getBean(TaskService::class);
        $this->nodeService = FrameInvoker::getBean(NodeService::class);
    }
}
