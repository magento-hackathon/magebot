<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Trigger
{
    public function activated() : bool;
}