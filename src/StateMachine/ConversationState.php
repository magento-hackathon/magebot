<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

final class ConversationState implements State
{
    /** @var string */
    private $name;
    /** @var Actions */
    private $entryActions;
    /** @var Actions */
    private $exitActions;

    public static function createWithoutActions(string $name)
    {
        return new static($name, new ActionList, new ActionList);
    }
    public static function createWithActions(string $name, Actions $entryActions, Actions $exitActions)
    {
        return new static($name, $entryActions, $exitActions);
    }
    public static function createWithEntryActions(string $name, Actions $entryActions)
    {
        return new static($name, $entryActions, new ActionList);
    }
    public static function createWithExitActions(string $name, Actions $exitActions)
    {
        return new static($name, new ActionList, $exitActions);
    }
    private function __construct(string $name, Actions $entryActions, Actions $exitActions)
    {
        $this->name = $name;
        $this->entryActions = $entryActions;
        $this->exitActions = $exitActions;
    }
    public function name() : string
    {
        return $this->name;
    }

    public function entryActions() : Actions
    {
        return $this->entryActions;
    }

    public function exitActions() : Actions
    {
        return $this->exitActions;
    }

    public function toArray() : array
    {
        // TODO: Implement toArray() method for Magento model
        return [];
    }
}