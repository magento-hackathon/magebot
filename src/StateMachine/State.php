<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface State
{
    public function name() : string;

    public function entryActions() : Actions;

    public function exitActions() : Actions;

    public function toArray() : array;
}