<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

/**
 * Context of conversation: last user input, custom variables etc.
 *
 * Attributes that need to be preserved across requests have to be serialized, others may be set by the bot implementation
 * (i.e. BotmanConversation)
 */
interface ConversationContext extends \Serializable
{
    public function setPersistentVar(string $key, $value);

    public function getPersistentVar(string $key);
}