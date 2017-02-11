<?php

namespace FireGento\MageBot;

use FireGento\MageBot\Botman\BotmanConfig;
use FireGento\MageBot\Botman\BotmanWebDriver;
use Magento\Framework\Shell\Driver;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;
use Mpociot\BotMan\DriverManager;
use Mpociot\BotMan\Interfaces\CacheInterface;

/**
 * Entry point for MageBot features (Magento independent)
 *
 * @package FireGento
 */
class MageBot
{
    /** @var BotmanConfig */
    private $config;
    /** @var  CacheInterface */
    private $cache;

    public function __construct(BotmanConfig $config, CacheInterface $cache)
    {
        $this->config = $config;
        $this->cache = $cache;
    }

    public function start()
    {
        DriverManager::loadDriver(BotmanWebDriver::class);
        $botman = BotManFactory::create($this->config->toArray(), $this->cache);

        $botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hello yourself. How cool is that?');
        });

        $botman->listen();

    }
}