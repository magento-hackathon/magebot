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
     * Side effects:
     *  - calls exit and entry actions
     *  - changes current state
     *
     * @return void
     */
    public function continue();
}