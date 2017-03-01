<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;
use FireGento\MageBot\StateMachine\Trigger;

/**
 * Minimal implementation of trigger factory. Assumes, $type is a class name and $parameters the constructor arguments
 */
final class NewTriggerFactory implements TriggerFactory
{
    public function create(string $type, array $parameters) : Trigger
    {
        return new $type(...array_values($parameters));
    }

}