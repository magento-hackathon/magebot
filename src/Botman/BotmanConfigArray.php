<?php

namespace FireGento\MageBot\Botman;

/**
 * Stub implementation for tests
 *
 * @package FireGento\MageBot\Botman
 */
class BotmanConfigArray extends \ArrayObject implements BotmanConfig
{
    public function __construct($data = [])
    {
        parent::__construct($data, 0, \ArrayIterator::class);
    }

    public function toArray() : array
    {
        return $this->getArrayCopy();
    }

}