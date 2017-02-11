<?php

namespace FireGento\MageBot;

use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;

/**
 * Entry point for MageBot features (Magento independent)
 *
 * @package FireGento
 */
class MageBot
{
    public function start()
    {
        $botman = BotManFactory::create([]);

        $botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hello yourself. How cool is that?');
        });

        $botman->listen();

    }
}