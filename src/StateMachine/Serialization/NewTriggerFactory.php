<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;

/**
 * Minimal implementation of trigger factory. Assumes, $type is a class name and $parameters a single constructor argument
 *
 * @todo merge with NewActionFactory ?
 */
final class NewTriggerFactory implements TriggerFactory
{
    public function create(string $type, array $parameters)
    {
        return new $type($parameters);
    }

}