<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

final class TransitionList extends \ArrayIterator implements Transitions
{
    public function __construct(Transition ...$transitions)
    {
        parent::__construct($transitions);
    }

    public function current() : Transition
    {
        return parent::current();
    }

    public function nextState(State $currentState) : State
    {
        foreach ($this as $transition) {
            if ($transition->triggeredAt($currentState)) {

                return $transition->target();
            }
        }

        return $currentState;
    }

}