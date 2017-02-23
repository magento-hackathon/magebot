<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan;
use FireGento\MageBot\StateMachine;

class BotmanConversation extends BotMan\Conversation
{
    /**
     * @var StateMachine\Conversation
     */
    private $stateMachine;

    public function run()
    {
        //TODO implement run()
        //$this->continue();
    }

    public function continue()
    {
        //$this->stateMachine->continue();
        //$this->bot->storeConversation($this, [$this, 'continue']);
    }

}