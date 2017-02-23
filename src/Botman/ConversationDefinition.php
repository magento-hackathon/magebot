<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\Conversation;

interface ConversationDefinition
{
    const START_MESSAGE_PATTERN_MATCH_ALL = '.*';

    /**
     * Pattern for Botman to listen to
     *
     * @return string
     */
    public function patternToStart() : string;

    /**
     * Factory method to instantiate conversation
     *
     * @return Conversation
     */
    public function create() : Conversation;

}