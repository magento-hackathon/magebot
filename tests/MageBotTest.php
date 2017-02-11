<?php

namespace FireGento\MageBot;

use PHPUnit\Framework\TestCase;

class MageBotTest extends TestCase
{
    public function testCanInstantiateMagebot()
    {
        $mageBot = new MageBot();
        $this->assertInstanceOf(MageBot::class, $mageBot);
    }
}
