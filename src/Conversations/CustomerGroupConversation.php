<?php

namespace FireGento\MageBot\Conversations;

use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Conversation;

class CustomerGroupConversation extends Conversation
{
    protected $reason;

    protected $weight;

    public function askReason()
    {
        $this->ask('Wobei können wir dir helfen?', function (Answer $answer) {

            $this->reason = $answer->getText();

            $this->say('Nice to meet you ' . $this->reason);
        });
    }

    public function run()
    {
        $this->askReason();
    }
}