<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;
use FireGento\MageBot\StateMachine\Action;

/**
 * Marker interface for Botman action.
 *
 * Actions that implement this interface MUST take a Botman instance as first constructor argument
 */
interface BotmanAction extends Action
{
}