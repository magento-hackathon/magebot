<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface StateMachine
{
    /**
     * @return State current state
     */
    public function state() : State;
    /**
     * Check triggers and apply first matching transition.
     *
     * Returns state machine in new state
     *
     * Side effects:
     *  - calls exit and entry actions
     *
     * @return StateMachine
     */
    public function continue() : StateMachine;
}