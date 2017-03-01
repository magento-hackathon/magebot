<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Action
{
    /**
     * Return type for serialization, will be used with ActionFactory to recreate instance
     *
     * @return string
     */
    public function type() : string;
    /**
     * Return parameters for serialization, will be used with ActionFactory to recreate instance.
     *
     * @return array Parameters as associative array with keys = constructor argument name, in the order they appear in the constructor
     */
    public function parameters() : array;

    /**
     * Execute action
     *
     * @return void
     */
    public function execute(ConversationContext $context);
}