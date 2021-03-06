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

    public function nextState(State $currentState, ConversationContext $context) : State
    {
        foreach ($this as $transition) {
            if ($transition->triggeredAt($currentState, $context)) {

                return $transition->target();
            }
        }

        return $currentState;
    }

    public function prepend(Transition $transition) : Transitions
    {
        return new static($transition, ...$this->getArrayCopy());
    }

}