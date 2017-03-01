<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine\Serialization;

use FireGento\MageBot\StateMachine\Action;
use FireGento\MageBot\StateMachine\ConversationContext;

class LazyLoadingAction implements Action
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Action
     */
    private $action;

    public function __construct(ActionFactory $actionFactory, string $type, array $parameters)
    {
        $this->actionFactory = $actionFactory;
        $this->type = $type;
        $this->parameters = $parameters;
    }

    public function type() : string
    {
        return $this->type;
    }

    public function parameters() : array
    {
        return $this->parameters;
    }

    public function execute(ConversationContext $context)
    {
        $this->loadedAction()->execute($context);
    }

    /**
     * @return Action
     */
    private function loadedAction() : Action
    {
        if ($this->action === null) {
            $this->action = $this->actionFactory->create($this->type, $this->parameters);
        }
        return $this->action;
    }


}