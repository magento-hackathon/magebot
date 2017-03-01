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
//        $this->continue(BotMan\Answer::create());
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
//        /** @var BotmanConversationContext $context */
//        $context = $this->stateMachine->context();
//        $context->setAnswer($answer);
//        $this->stateMachine->continue();
//        $this->bot->storeConversation($this, [$this, 'continue']);
    }

}