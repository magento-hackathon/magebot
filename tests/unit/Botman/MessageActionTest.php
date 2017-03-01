<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\ConversationContext;
use Mpociot\BotMan\BotMan;
use PHPUnit\Framework\TestCase;

class MessageActionTest extends TestCase
{
    public function testDefinition()
    {
        $action = new MessageAction($this->createMock(BotMan::class), 'Hello, world!');
        static::assertEquals(MessageAction::class, $action->type());
        static::assertEquals(['message' => 'Hello, world!'], $action->parameters());
    }
    public function testSendMessage()
    {
        $botMock = $this->createMock(BotMan::class);
        $botMock->expects(static::once())
            ->method('reply')
            ->with('Hello, world!');
        $action = new MessageAction($botMock, 'Hello, world!');
        $action->execute($this->createMock(ConversationContext::class));
    }
}