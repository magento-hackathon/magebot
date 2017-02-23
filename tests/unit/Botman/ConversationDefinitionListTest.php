<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\BotMan;
use PHPUnit\Framework\TestCase;

class ConversationDefinitionListTest extends TestCase
{
    public function testRegisterConversations()
    {
        $botman = $this->createMock(BotMan::class);
        $botman->expects(static::exactly(2))
            ->method('hears')
            ->withConsecutive(
                ['pattern1', static::isInstanceOf(\Closure::class)],
                ['pattern2', static::isInstanceOf(\Closure::class)]
            )->willReturnCallback(
                function($pattern, $callback) use ($botman) {
                    $callback($botman);
                }
            );
        $botman->expects(static::exactly(2))
            ->method('startConversation')
            ->with(static::isInstanceOf(BotmanConversation::class));
        $conversationDefinitionList = new ConversationDefinitionList(
            $this->conversationDefinitionStub('pattern1'), $this->conversationDefinitionStub('pattern2')
        );
        $conversationDefinitionList->register($botman);
    }

    private function conversationDefinitionStub(string $pattern1) : ConversationDefinition
    {
        $stub = $this->createMock(ConversationDefinition::class);
        $stub->method('patternToStart')->willReturn($pattern1);
        return $stub;
    }
}