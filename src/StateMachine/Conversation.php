<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

class Conversation implements StateMachine
{
    /** @var ConversationContext */
    private $context;
    /** @var State */
    private $currentState;
    /** @var States  */
    private $states;
    /** @var Transitions */
    private $transitions;

    private function __construct(States $states, Transitions $transitions, State $currentState, ConversationContext $context)
    {
        if (!$states->contains($currentState)) {
            throw new \DomainException('Current state must be element of known states');
        }
        $this->context = $context;
        $this->states = $states;
        $this->currentState = $currentState;
        $this->transitions = $transitions;
    }

    public static function createUnstarted(States $states, Transitions $transitions, State $initialState, ConversationContext $context) : Conversation
    {
        if (!$states->contains($initialState)) {
            throw new \DomainException('Initial state must be element of known states');
        }
        $unstarted = new UnstartedState();
        $initialTransition = new InitialTransition($initialState);
        return static::create($states->with($unstarted), $transitions->prepend($initialTransition), $unstarted, $context);
    }

    public static function create(States $states, Transitions $transitions, State $currentState, ConversationContext $context) : Conversation
    {
        return new static($states, $transitions, $currentState, $context);
    }

    public function context() : ConversationContext
    {
        return $this->context;
    }

    public function state() : State
    {
        return $this->currentState;
    }

    //TODO consider removing the states reference, it should only be needed in ConversationDefinition if at all
    public function states() : States
    {
        return $this->states;
    }

    public function continue() : StateMachine
    {
        $nextState = $this->transitions->nextState($this->currentState, $this->context);
        if ($nextState !== $this->currentState) {
            $this->currentState->exitActions()->executeAll($this->context);
            $nextState->entryActions()->executeAll($this->context);
            return static::create($this->states, $this->transitions, $nextState, $this->context);
        }
        return $this;
    }
}