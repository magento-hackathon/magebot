<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;
use FireGento\MageBot\StateMachine\Action;

/**
 * Minimal implementation of action factory. Assumes, $type is a class name and $parameters a single constructor argument
 */
final class NewActionFactory implements ActionFactory
{
    public function create(string $type, array $parameters) : Action
    {
        return new $type($parameters);
    }

}