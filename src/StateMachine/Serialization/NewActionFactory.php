<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;

/**
 * Minimal implementation of action factory. Assumes, $type is a class name and $parameters a single constructor argument
 */
final class NewActionFactory implements ActionFactory
{
    public function create(string $type, array $parameters)
    {
        return new $type($parameters);
    }

}