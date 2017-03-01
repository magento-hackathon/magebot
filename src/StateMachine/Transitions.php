<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Transitions extends \Iterator
{
    public function current() : Transition;

    public function nextState(State $currentState, ConversationContext $context) : State;

    public function prepend(Transition $transition) : Transitions;
}