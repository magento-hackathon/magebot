<?php

namespace FireGento\MageBot;

use FireGento\MageBot\Botman\BotmanConfig;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;

/**
 * Entry point for MageBot features (Magento independent)
 *
 * @package FireGento
 */
class MageBot
{
    /** @var BotmanConfig */
    private $config;

    public function __construct(BotmanConfig $config)
    {
        $this->config = $config;
    }

    public function start()
    {
        $botman = BotManFactory::create($this->config->toArray());

        $botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hello yourself. How cool is that?');
        });

        $botman->listen();

    }
}