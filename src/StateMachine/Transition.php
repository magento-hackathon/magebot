<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Transition
{
    public function name() : string;

    public function target() : State;

    public function triggeredAt(State $currentState) : bool;

    public function toArray() : array;
}