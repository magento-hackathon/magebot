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
     * Return parameters for serialization, will be used with ActionFactory to recreate instance
     *
     * @return array
     */
    public function parameters() : array;

    /**
     * Execute action
     *
     * @return void
     */
    public function execute();
}