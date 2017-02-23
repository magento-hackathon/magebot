<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

class Conversation implements StateMachine
{
    /** @var State */
    private $currentState;
    /** @var States  */
    private $states;
    /** @var Transitions */
    private $transitions;

    private function __construct(States $states, Transitions $transitions, State $currentState)
    {
        if (!$states->contains($currentState)) {
            throw new \DomainException('Current state must be element of known states');
        }
        $this->states = $states;
        $this->currentState = $currentState;
        $this->transitions = $transitions;
    }

    public static function createUnstarted(States $states, Transitions $transitions, State $initialState) : Conversation
    {
        if (!$states->contains($initialState)) {
            throw new \DomainException('Initial state must be element of known states');
        }
        $unstarted = new UnstartedState();
        $initialTransition = new InitialTransition($initialState);
        return static::create($states->with($unstarted), $transitions->prepend($initialTransition), $unstarted);
    }

    public static function create(States $states, Transitions $transitions, State $currentState) : Conversation
    {
        return new static($states, $transitions, $currentState, $currentState);
    }

    public function state() : State
    {
        return $this->currentState;
    }

    public function states() : States
    {
        return $this->states;
    }

    public function continue() : StateMachine
    {
        $nextState = $this->transitions->nextState($this->currentState);
        if ($nextState !== $this->currentState) {
            $this->currentState->exitActions()->executeAll();
            $nextState->entryActions()->executeAll();
            return static::create($this->states, $this->transitions, $nextState);
        }
        return $this;
    }
}