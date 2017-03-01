<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;
use FireGento\MageBot\StateMachine\Trigger;

/**
 * Trigger factory compatible with FakeTrigger. Assumes, $type is a class name and $parameters a single constructor argument
 */
final class FakeTriggerFactory implements TriggerFactory
{
    public function create(string $type, array $parameters) : Trigger
    {
        return new $type($parameters);
    }

}