<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Action
{
    public function execute();
}