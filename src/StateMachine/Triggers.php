<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

interface Triggers extends \Iterator, \JsonSerializable
{
    public function current() : Trigger;

    public function anyActivated(ConversationContext $context) : bool;
}