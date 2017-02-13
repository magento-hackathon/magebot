<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

class Conversation implements StateMachine
{
    /** @var State */
    private $initialState;
    /** @var State */
    private $currentState;
    /** @var States  */
    private $states;
    /** @var Transitions */
    private $transitions;

    private function __construct(States $states, Transitions $transitions, State $initialState, State $currentState)
    {
        if (!$states->contains($initialState)) {
            throw new \DomainException('Initial state must be element of known states');
        }
        if (!$states->contains($currentState)) {
            throw new \DomainException('Current state must be element of known states');
        }
        $this->states = $states;
        $this->initialState = $initialState;
        $this->currentState = $currentState;
        $this->transitions = $transitions;
    }

    public static function create(States $states, Transitions $transitions, State $initialState) : Conversation
    {
        return new static($states, $transitions, $initialState, $initialState);
    }

    public static function createWithState(States $states, Transitions $transitions, $initialState, $currentState) : Conversation
    {
        return new static($states, $transitions, $initialState, $currentState);
    }

    public function state() : State
    {
        return $this->currentState;
    }

    public function states() : States
    {
        return $this->states;
    }

    public function continue()
    {
        $nextState = $this->transitions->nextState($this->currentState);
        if ($nextState !== $this->currentState) {
            $this->currentState->exitActions()->executeAll();
            $this->currentState = $nextState;
            $this->currentState->entryActions()->executeAll();
        }
    }
}