<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

final class ActionList extends \ArrayIterator implements Actions
{
    public function __construct(Action ...$actions)
    {
        parent::__construct($actions);
    }

    public function current() : Action
    {
        return parent::current();
    }

    public function executeAll()
    {
        foreach ($this as $action) {
            $action->execute();
        }
    }

}