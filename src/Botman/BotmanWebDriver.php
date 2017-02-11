<?php

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Drivers\Driver;
use Mpociot\BotMan\Message;
use Mpociot\BotMan\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * Chatbot driver for testing. Takes messages from GET parameters and outputs answers directly
 *
 * @package FireGento\MageBot\Botman
 */
class BotmanWebDriver extends Driver
{
    const DRIVER_NAME = 'Web Driver';
    /**
     * @var Request
     */
    private $request;

    public function buildPayload(Request $request)
    {
        $this->request = $request;
    }

    public function matchesRequest()
    {
        return $this->request->get('test');
    }

    public function getMessages()
    {
        return [
            new Message($this->request->get('msg'), 'dummy user', 'dummy channel')
        ];
    }

    public function isBot()
    {
        return false;
    }

    public function isConfigured()
    {
        return true;
    }

    public function getUser(Message $matchingMessage)
    {
        return new User();
    }

    public function getConversationAnswer(Message $message)
    {
        return Answer::create($message->getMessage())->setMessage($message);
    }

    public function reply($message, $matchingMessage, $additionalParameters = [])
    {
        // cannot use DI with botman drivers, so just echo:
        echo $message;
    }

    public function getName()
    {
        return self::DRIVER_NAME;
    }

}