<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;

use FireGento\MageBot\StateMachine\Action;
use FireGento\MageBot\StateMachine\ConversationContext;

final class SerializableAction implements \Serializable, \JsonSerializable, Action
{
    /**
     * @var ActionFactory
     */
    private static $actionFactory;

    /**
     * Set action factory instance to be used for unserialization
     *
     * @param ActionFactory $actionFactory
     */
    public static function setActionFactory(ActionFactory $actionFactory)
    {
        static::$actionFactory = $actionFactory;
    }

    /**
     * Reset action factory instance to dummy instance. Called once on class load (see below) and in tests
     */
    public static function resetActionFactory()
    {
        SerializableAction::setActionFactory(new class implements ActionFactory {
            public function create(string $type, array $parameters) : Action
            {
                throw new \RuntimeException(
                    'SerializableAction::setActionFactory() must be called with a real action factory before using serialized actions'
                );
            }
        });
    }
    /**
     * @var Action
     */
    private $action;

    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->action->type(),
            'parameters' => $this->action->parameters()
        ];
    }

    public function serialize() : string
    {
        return json_encode(
            array_values($this->jsonSerialize())
        );
    }

    public function unserialize($serialized)
    {
        list($type, $params) = json_decode($serialized, true);
        $this->action = static::$actionFactory->create($type, $params);
    }

    public function type() : string
    {
        return get_class($this->action);
    }

    public function parameters() : array
    {
        return $this->action->parameters();
    }

    public function execute(ConversationContext $context)
    {
        $this->action->execute($context);
    }

}
SerializableAction::resetActionFactory();