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

    public function anyActivated(ConversationContext $context) : bool
    {
        foreach ($this as $trigger) {
            if ($trigger->activated($context)) {
                return true;
            }
        }
        return false;
    }

    public function serialize()
    {
        $this->makeTriggersSerializable();
        return parent::serialize();
    }

    public function jsonSerialize()
    {
        $this->makeTriggersSerializable();
        return $this->getArrayCopy();
    }

    private function makeTriggersSerializable()
    {
        foreach ($this as $key => $trigger) {
            $this[$key] = new SerializableTrigger($trigger);
        }
    }


}