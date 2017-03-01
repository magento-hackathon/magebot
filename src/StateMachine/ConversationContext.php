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
    /**
     * Set a custom variable that will be persisted for the conversation. It should be a serializable value
     *
     * @return void
     */
    public function setPersistentVar(string $key, $value);

    /**
     * Return true if persistent variable with given name exists
     */
    public function hasPersistentVar(string $key) : bool;

    /**
     * Return a persisted custom variable by name
     *
     * @return mixed
     */
    public function getPersistentVar(string $key);
}