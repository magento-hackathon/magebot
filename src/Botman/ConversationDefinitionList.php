<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\BotMan;

class ConversationDefinitionList extends \ArrayIterator implements ConversationDefinitions
{
    public function __construct(ConversationDefinition ...$conversationDefinitions)
    {
        parent::__construct($conversationDefinitions);
    }

    public function current() : ConversationDefinition
    {
        return parent::current();
    }

    public function register(BotMan $botman)
    {
        foreach ($this as $conversationDefinition) {
            $botman->hears($conversationDefinition->patternToStart(), function(BotMan $bot) use ($conversationDefinition) {
                $bot->startConversation(new BotmanConversation($bot, $conversationDefinition->create()));
            });
        }
    }

}