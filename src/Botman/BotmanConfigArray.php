<?php

namespace FireGento\MageBot\Botman;

/**
 * Stub implementation for tests
 *
 * @package FireGento\MageBot\Botman
 */
class BotmanConfigArray extends \ArrayObject implements BotmanConfig
{
    public function toArray() : array
    {
        return $this->getArrayCopy();
    }

}