<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use FireGento\MageBot\StateMachine\Serialization\SerializableTrigger;

final class TriggerList extends \ArrayIterator implements Triggers
{
    public function __construct(Trigger ...$actions)
    {
        parent::__construct($actions);
    }

    public function current() : Trigger
    {
        return parent::current();
    }

    public function anyActivated() : bool
    {
        foreach ($this as $trigger) {
            if ($trigger->activated()) {
                return true;
            }
        }
        return false;
    }

    public function serialize()
    {
        foreach ($this as $key => $trigger) {
            $this[$key] = new SerializableTrigger($trigger);
        }
        return parent::serialize();
    }

}