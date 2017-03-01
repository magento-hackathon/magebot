<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

final class FakeAction implements Action
{
    /**
     * @var array parameters for factory
     */
    private $parameters;
    /**
     * @var int counter for execute() calls
     */
    private $timesExecuted = 0;

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

    public function execute(ConversationContext $context)
    {
        ++$this->timesExecuted;
    }

    /**
     * @return int
     */
    public function timesExecuted(): int
    {
        return $this->timesExecuted;
    }

}