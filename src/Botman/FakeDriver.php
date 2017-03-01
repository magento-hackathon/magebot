<?php

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Interfaces\DriverInterface;
use Mpociot\BotMan\Message;
use Mpociot\BotMan\Question;
use Mpociot\BotMan\User;

/**
 * A fake driver for tests. Must be used with ProxyDriver
 */
class FakeDriver implements DriverInterface
{
    public $matchesRequest = true;
    public $messages = [];
    public $isBot = false;
    public $isConfigured = true;

    private $botMessages = [];
    private $botIsTyping = false;

    public function matchesRequest()
    {
        return $this->matchesRequest;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function isBot()
    {
        return $this->isBot;
    }

    public function isConfigured()
    {
        return $this->isConfigured;
    }

    public function getUser(Message $matchingMessage)
    {
        return new User($matchingMessage->getUser());
    }

    public function getConversationAnswer(Message $message)
    {
        return Answer::create($message->getMessage())->setMessage($message);
    }

    public function reply($message, $matchingMessage, $additionalParameters = [])
    {
        $this->botMessages[] = $message;
        return $this;
    }

    public function getName()
    {
        return 'Fake Driver';
    }

    public function types(Message $matchingMessage)
    {
        $this->botIsTyping = true;
    }

    /**
     * @return bool
     */
    public function isBotTyping()
    {
        return $this->botIsTyping;
    }

    /**
     * @return string[]|Question[]
     */
    public function getBotMessages()
    {
        return $this->botMessages;
    }
}