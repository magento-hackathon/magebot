<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Actions extends \Iterator, \Countable, \JsonSerializable
{
    public function current() : Action;

    public function executeAll();
}