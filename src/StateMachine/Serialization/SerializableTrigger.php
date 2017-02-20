<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;

use FireGento\MageBot\StateMachine\Trigger;

final class SerializableTrigger implements \Serializable, Trigger
{
    /**
     * @var TriggerFactory
     */
    private static $triggerFactory;

    /**
     * Set trigger factory instance to be used for unserialization
     *
     * @param TriggerFactory $triggerFactory
     */
    public static function setTriggerFactory(TriggerFactory $triggerFactory)
    {
        static::$triggerFactory = $triggerFactory;
    }

    /**
     * Reset trigger factory instance to dummy instance. Called once on class load (see below) and in tests
     */
    public static function resetTriggerFactory()
    {
        SerializableTrigger::setTriggerFactory(new class implements TriggerFactory {
            public function create(string $type, array $parameters)
            {
                throw new \RuntimeException(
                    'SerializableTrigger::setTriggerFactory() must be called with a real trigger factory before using serialized triggers'
                );
            }
        });
    }
    /**
     * @var Trigger
     */
    private $trigger;

    public function __construct(Trigger $trigger)
    {
        $this->trigger = $trigger;
    }
    public function serialize() : string
    {
        return json_encode(
            [
                $this->trigger->type(),
                $this->trigger->parameters()
            ]
        );
    }

    public function unserialize($serialized)
    {
        list($type, $params) = json_decode($serialized, true);
        $this->trigger = static::$triggerFactory->create($type, $params);
    }

    public function type() : string
    {
        return get_class($this->trigger);
    }

    public function parameters() : array
    {
        return $this->trigger->parameters();
    }

    public function activated() : bool
    {
        return $this->trigger->activated();
    }


}
SerializableTrigger::resetTriggerFactory();