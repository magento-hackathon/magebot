<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

final class FakeTrigger implements Trigger
{
    /**
     * @var array parameters for factory
     */
    private $parameters;

    /**
     * @param array $parameters arbitrary array of parameters. Set $parameters['activated'] if the trigger should be activated
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function type() : string
    {
        return __CLASS__;
    }

    public function parameters() : array
    {
        return $this->parameters;
    }

    public function activated(ConversationContext $context) : bool
    {
        return !empty($this->parameters['activated']);
    }


}