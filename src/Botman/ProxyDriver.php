<?php
namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\Drivers\NullDriver;
use Mpociot\BotMan\Http\Curl;
use Mpociot\BotMan\Interfaces\DriverInterface;
use Mpociot\BotMan\Message;
use Symfony\Component\HttpFoundation\Request;

/**
 * A driver that acts as a proxy for a global driver instance. Useful for mock/fake drivers in integration tests
 */
final class ProxyDriver implements DriverInterface
{
    /**
     * @var DriverInterface
     */
    private static $instance;

    public static function setInstance(DriverInterface $driver)
    {
        self::$instance = $driver;
    }

    /**
     * @return DriverInterface
     */
    private static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new NullDriver(new Request, [], new Curl);
        }
        return self::$instance;
    }

    public function matchesRequest()
    {
        return self::instance()->matchesRequest();
    }

    public function getMessages()
    {
        return self::instance()->getMessages();
    }

    public function isBot()
    {
        return self::instance()->isBot();
    }

    public function isConfigured()
    {
        return self::instance()->isConfigured();
    }

    public function getUser(Message $matchingMessage)
    {
        return self::instance()->getUser($matchingMessage);
    }

    public function getConversationAnswer(Message $message)
    {
        return self::instance()->getConversationAnswer($message);
    }

    public function reply($message, $matchingMessage, $additionalParameters = [])
    {
        return self::instance()->reply($message, $matchingMessage, $additionalParameters);
    }

    public function getName()
    {
        return self::instance()->getName();
    }

    public function types(Message $matchingMessage)
    {
        return self::instance()->types($matchingMessage);
    }

}