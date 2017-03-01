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

    public function __construct(BotMan\BotMan $bot, StateMachine\Conversation $stateMachine)
    {
        $this->bot = $bot;
        $this->stateMachine = $stateMachine;
    }

    public function run()
    {
        $this->continue(BotMan\Answer::create());
    }

    /**
     * continue() is used as central "next" callback for Botman. The first parameter is always an Answer object,
     * the last one is the conversation itself (not needed here)
     *
     * Additional parameters are added for conditional callbacks with pattern matching, but we do not use these,
     * everything is routed through continue() and handled by the state machine.
     *
     * @see \Mpociot\BotMan\BotMan::loadActiveConversation()
     *
     * @param BotMan\Answer $answer
     */
    public function continue(BotMan\Answer $answer)
    {
        /** @var BotmanConversationContext $context */
        $context = $this->stateMachine->context();
        $context->setAnswer($answer);
        $this->stateMachine = $this->stateMachine->continue();
        //in PHP 7.1: Closure::fromCallable()
        $next = function(BotMan\Answer $answer) {
            $this->continue($answer);
        };
        $this->bot->storeConversation($this, $next);
    }

}