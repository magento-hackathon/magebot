<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface ActionFactory
{
    public function create(string $type, array $parameters);
}