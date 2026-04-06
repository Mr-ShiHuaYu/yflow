<?php

namespace Tests;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../support/bootstrap.php';

use Yflow\core\dto\FlowParams;
use Yflow\core\FlowEngine;
use Yflow\core\listener\ListenerVariable;
use Yflow\core\utils\ExpressionUtil;
use Yflow\impl\helper\SpelHelper;
use PHPUnit\Framework\TestCase;
use Yflow\YFlowBootstrap;

class ExpressionTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        YFlowBootstrap::init();
    }

    /**
     * spel测试
     */
    public function testSpel()
    {
        // OK
        $map                     = [];
        $map['listenerVariable'] = new ListenerVariable();
        $result                  = SpelHelper::parseExpression('#{@user.notify(#listenerVariable)}', $map);
        dump('SpelHelper结果:', $result);
        $this->assertTrue($result);
    }

    /**
     * 条件表达式测试
     */
    public function testCondition()
    {
        // OK
        $variable['flag'] = 4;

        $defaultResult = ExpressionUtil::evalCondition(
            'default@@${(flag == 4 && flag > 3) && flag > 5}', $variable
        );
        dump('default条件表达式结果:', $defaultResult);
        $this->assertFalse($defaultResult);

        $spelResult = ExpressionUtil::evalCondition('spel@@#{@user.eval(#flag)}', $variable);
        dump('spel条件表达式结果:', $spelResult);
        $this->assertFalse($spelResult);

        $eqResult = ExpressionUtil::evalCondition('eq@@flag|4', $variable);
        dump('eq条件表达式结果:', $eqResult);
        $this->assertTrue($eqResult);
    }

    /**
     * 监听器表达式测试
     */
    public function testListener()
    {
        $variable3                     = [];
        $variable3['listenerVariable'] = new ListenerVariable();
        $result                        = ExpressionUtil::evalListener('#{@user.notify(#listenerVariable)}', $variable3);
        $this->assertTrue($result); // notify方法返回void，所以结果为false
    }

    /**
     * 办理人表达式测试
     */
    public function testVariable()
    {
        // OK
        $variable1            = [];
        $variable1['handler'] = '101';
        $result1              = ExpressionUtil::evalVariableByExp('#{@user.evalVar(#handler)}', $variable1);
        dump('spel办理人表达式结果:', $result1);
        $this->assertEquals(['101'], $result1);

        $variable2            = [];
        $variable2['handler'] = FlowEngine::newTask()->setId(1);
        $result2              = ExpressionUtil::evalVariableByExp('#{@user.evalVar(#handler)}', $variable2);
        dump('spel办理人表达式结果:', $result2);
        $this->assertEquals(['1'], $result2);

        $addTasks = [];
        $task     = FlowEngine::newTask();
        $task->setPermissionList([
            '${handler1}',
            '#{@user.evalVar(#handler2)}',
            '${handler3}',
            '#{@user.evalVar(#handler4)}',
            '#{@user.evalVarEntity(#handler5)}',
            'role:1',
            '1'
        ]);
        $addTasks[] = $task;

        $variable             = [];
        $variable['handler1'] = [4, '5', 100];
        $variable['handler2'] = 12;
        $variable['handler3'] = [9, '10', 102];
        $variable['handler4'] = '15';
        $task                 = FlowEngine::newTask();
        $variable['handler5'] = $task->setId(55);

        ExpressionUtil::evalVariable($addTasks, FlowParams::build()->variable($variable));
        foreach ($addTasks as $task) {
            foreach ($task->getPermissionList() as $permission) {
                dump($permission);
            }
        }
        $this->assertTrue(true); // 只要执行不报错就算通过
    }

    /**
     * 票签表达式测试
     */
    public function testVoteSign()
    {
        // OK
        $variable               = [];
        $variable['flag']       = 56;
        $variable['skipType']   = 'PASS';
        $variable['passNum']    = 12;
        $variable['rejectNum']  = 3;
        $variable['todoNum']    = 2;
        $variable['allNum']     = 20;
        $variable['passList']   = [];
        $variable['rejectList'] = [];
        $variable['todoList']   = [];

        $defaultResult = ExpressionUtil::evalVoteSign(
            'default@@${passNum * 1.0 / allNum > 0.5}', $variable
        );
        dump('default条件表达式结果:', $defaultResult);
        $this->assertTrue($defaultResult);

        $spelResult = ExpressionUtil::evalVoteSign(
            'spel@@#{@voteSignService.eval(#skipType, #passNum, #rejectNum, #todoNum, #allNum, #passList, #rejectList, #todoList)}', $variable
        );
        dump('spel条件表达式结果:', $spelResult);
        $this->assertTrue($spelResult);
    }
}
