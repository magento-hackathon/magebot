<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

/**
 * A state machine is in this state before it has been started
 */
final class UnstartedState implements State
{
    public function name() : string
    {
        return '';
    }

    public function entryActions() : Actions
    {
        return new ActionList();
    }

    public function exitActions() : Actions
    {
        return new ActionList();
    }

    public function toArray() : array
    {
        return [];
    }
}