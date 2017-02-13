<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

final class ConversationTransition implements Transition
{
    /** @var string */
    private $name;
    /** @var State */
    private $source;
    /** @var State */
    private $target;
    /** @var Triggers */
    private $triggers;

    public static function createAnonymous(State $from, State $to, Triggers $triggers)
    {
        return new static(\uniqid('transition-', true), $from, $to, $triggers);
    }
    public static function create(string $name, State $source, State $target, Triggers $triggers)
    {
        return new static($name, $source, $target, $triggers);
    }
    private function __construct(string $name, State $source, State $target, Triggers $triggers)
    {
        $this->name = $name;
        $this->source = $source;
        $this->target = $target;
        $this->triggers = $triggers;
    }
    public function name() : string
    {
        return $this->name;
    }

    public function target() : State
    {
        return $this->target;
    }

    public function triggeredAt(State $currentState) : bool
    {
        return $currentState == $this->source && $this->triggers->anyActivated();
    }

    public function toArray() : array
    {
        // TODO: Implement toArray() method for Magento model
        return [];
    }
}