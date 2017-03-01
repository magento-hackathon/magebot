<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use PHPUnit\Framework\TestCase;

class BotmanConversationContextTest extends TestCase
{
    public function testSerialization()
    {
        $context = new BotmanConversationContext();
        $context->setPersistentVar('Persist me', 'I am a variable!');
        $serializedContext = serialize($context);
        /** @var BotmanConversationContext $unserializedContext */
        $unserializedContext = unserialize($serializedContext, ['allowed_classes' => [BotmanConversationContext::class]]);
        static::assertTrue($unserializedContext->hasPersistentVar('Persist me'));
        static::assertEquals('I am a variable!', $unserializedContext->getPersistentVar('Persist me'));
    }

    public function testUnsetVariables()
    {
        $context = new BotmanConversationContext();
        static::expectException(\RuntimeException::class);
        $context->getPersistentVar('I do not exist');
    }
}