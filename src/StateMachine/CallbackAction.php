<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

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


    public function execute()
    {
        ($this->callback)();
    }

}