<?php

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\BotMan;

interface BotmanConfig
{
    /**
     * @return array
     */
    public function toArray() : array;

    /**
     * @param Botman $botman
     */
    public function configureBotMan(BotMan $botman);
}