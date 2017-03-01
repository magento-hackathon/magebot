<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

/**
 * Action that executes arbitrary callback. This cannot be converted to a serializable action, so it cannot be used
 * in a conversation. Its main purpose is testing.
 */
final class CallbackAction implements Action
{
    /** @var callable */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function type() : string
    {
        return __CLASS__;
    }

    public function parameters() : array
    {
        throw new \RuntimeException("CallbackAction type is not serializable");
    }


    public function execute(ConversationContext $context)
    {
        ($this->callback)();
    }

}