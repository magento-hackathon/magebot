<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\Action;
use FireGento\MageBot\StateMachine\Serialization\ActionFactory;
use FireGento\MageBot\StateMachine\Serialization\NewActionFactory;
use Mpociot\BotMan\BotMan;

/**
 * Variant of NewActionFactory with special case for BotmanAction types
 *
 * @see NewActionFactory
 * @see BotmanAction
 */
class BotmanActionFactory implements ActionFactory
{
    private $botman;
    public function __construct(BotMan $botman)
    {
        $this->botman = $botman;
    }

    public function create(string $type, array $parameters) : Action
    {
        if (is_a($type, BotmanAction::class, true)) {
            return new $type($this->botman, ...array_values($parameters));
        }
        return new $type(...array_values($parameters));
    }
}