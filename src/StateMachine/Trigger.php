<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Trigger
{
    /**
     * @return bool Return true if trigger condition is met
     */
    public function activated(ConversationContext $context) : bool;

    /**
     * Return type for serialization, will be used with ActionFactory to recreate instance
     *
     * @return string
     */
    public function type() : string;

    /**
     * Return parameters for serialization, will be used with TriggerFactory to recreate instance
     *
     * @return array Parameters as associative array with keys = constructor argument name, in the order they appear in the constructor
     */
    public function parameters() : array;

}