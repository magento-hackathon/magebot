<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;

use FireGento\MageBot\StateMachine\Trigger;

class LazyLoadingTrigger implements Trigger
{
    /**
     * @var TriggerFactory
     */
    private $triggerFactory;
    /**
     * @var string
     */
    private $type;
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var Trigger
     */
    private $trigger;

    public function __construct(TriggerFactory $triggerFactory, string $type, array $parameters)
    {
        $this->triggerFactory = $triggerFactory;
        $this->type = $type;
        $this->parameters = $parameters;
    }

    public function activated() : bool
    {
        return $this->loadedTrigger()->activated();
    }

    public function type() : string
    {
        return $this->type;
    }

    public function parameters() : array
    {
        return $this->parameters;
    }

    /**
     * @return Trigger
     */
    private function loadedTrigger():Trigger
    {
        if ($this->trigger === null) {
            $this->trigger = $this->triggerFactory->create($this->type, $this->parameters);
        }
        return $this->trigger;
    }
}