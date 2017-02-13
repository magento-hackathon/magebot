<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface States extends \Iterator
{
    public function current() : State;

    public function contains(State $state) : bool;
}