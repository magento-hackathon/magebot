<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;


final class InitialTransition implements Transition
{
    /**
     * @var State
     */
    private $initialState;

    public function __construct(State $initialState)
    {
        $this->initialState = $initialState;
    }

    public function name() : string
    {
        return '';
    }

    public function target() : State
    {
        return $this->initialState;
    }

    public function triggeredAt(State $currentState) : bool
    {
        return $currentState instanceof UnstartedState;
    }

    public function toArray() : array
    {
        return [];
    }

}