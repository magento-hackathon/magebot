<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;
use FireGento\MageBot\StateMachine\Action;

/**
 * Action factories are used to instantiate actions on demand based on stored type and parameter
 *
 * @see SerializableAction
 */
interface ActionFactory
{
    public function create(string $type, array $parameters) : Action;
}