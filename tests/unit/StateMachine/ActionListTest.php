<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use PHPUnit\Framework\TestCase;

class ActionListTest extends TestCase
{
    public function testExecuteAll()
    {
        $actionList = new ActionList($this->actionExpectedToBeCalled(), $this->actionExpectedToBeCalled());
        $actionList->executeAll();
    }

    /**
     * @return CallbackAction
     */
    private function actionExpectedToBeCalled():CallbackAction
    {
        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();
        $callbackMock->expects(static::once())->method('__invoke');
        return new CallbackAction($callbackMock);
    }
}
