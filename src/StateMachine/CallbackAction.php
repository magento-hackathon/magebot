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

    public function execute()
    {
        ($this->callback)();
    }

}