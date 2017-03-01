<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\Answer;
use Mpociot\BotMan\BotMan;
use PHPUnit\Framework\TestCase;

class AnswerTriggerTest extends TestCase
{
    public function testDefinition()
    {
        $trigger = new AnswerTrigger('foo');
        static::assertEquals(AnswerTrigger::class, $trigger->type());
        static::assertEquals(['value' => 'foo'], $trigger->parameters());
    }

    public function testActivatedByBotmanMessage()
    {
        $context = new BotmanConversationContext();
        $context->setAnswer(Answer::create('foo'));
        $trigger = new AnswerTrigger('foo');
        static::assertTrue($trigger->activated($context));
    }

    public function testNotActivatedByNotMatchingBotmanMessage()
    {
        $context = new BotmanConversationContext();
        $context->setAnswer(Answer::create('fooman'));
        $trigger = new AnswerTrigger('foo');
        static::assertFalse($trigger->activated($context));
    }
}