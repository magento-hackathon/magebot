<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use PHPUnit\Framework\TestCase;

class CallbackActionTest extends TestCase
{
    public function testExecute()
    {
        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();
        $callbackMock->expects(static::once())->method('__invoke');
        $action = new CallbackAction($callbackMock);
        $action->execute($this->createMock(ConversationContext::class));
    }
}
