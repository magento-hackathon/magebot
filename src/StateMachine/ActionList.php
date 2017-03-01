<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use FireGento\MageBot\StateMachine\Serialization\SerializableAction;

final class ActionList extends \ArrayIterator implements Actions
{
    public function __construct(Action ...$actions)
    {
        parent::__construct($actions);
    }

    public function current() : Action
    {
        return parent::current();
    }

    public function executeAll(ConversationContext $context)
    {
        foreach ($this as $action) {
            $action->execute($context);
        }
    }

    public function serialize()
    {
        $this->makeActionsSerializable();
        return parent::serialize();
    }

    public function jsonSerialize()
    {
        $this->makeActionsSerializable();
        return $this->getArrayCopy();
    }

    private function makeActionsSerializable()
    {
        foreach ($this as $key => $action) {
            $this[$key] = new SerializableAction($action);
        }
    }


}