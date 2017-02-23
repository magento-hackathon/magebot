<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\BotMan;

interface ConversationDefinitions extends \Iterator
{
    public function current() : ConversationDefinition;

    /**
     * Register conversations (let Botman listen to start patterns)
     *
     * @return void
     */
    public function register(BotMan $botman);

}