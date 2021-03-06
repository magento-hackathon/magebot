<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\ConversationContext;
use Mpociot\BotMan\BotMan;

/**
 * Action for simple text message
 */
class MessageAction implements BotmanAction
{
    const PARAM_MESSAGE = 'message';
    /**
     * @var BotMan
     */
    private $botman;

    private $message;

    public function __construct(BotMan $botman, string $message)
    {
        $this->botman = $botman;
        $this->message = $message;
    }

    public function type() : string
    {
        return __CLASS__;
    }

    public function parameters() : array
    {
        return [
            self::PARAM_MESSAGE => $this->message,
        ];
    }

    public function execute(ConversationContext $context)
    {
        $this->botman->reply($this->message);
    }

}