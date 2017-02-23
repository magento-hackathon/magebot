<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;
use FireGento\MageBot\StateMachine\Trigger;

/**
 * Trigger factories are used to instantiate triggers on demand based on stored type and parameter
 *
 * @see SerializableTrigger
 */
interface TriggerFactory
{
    public function create(string $type, array $parameters) : Trigger;
}