<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

/**
 * A trigger that is always or never triggered, useful for tests
 */
final class FixedTrigger implements Trigger
{
    const PARAM_MATCHES = 'matches';
    /** @var bool */
    private $matches;

    public function __construct(bool $matches)
    {
        $this->matches = $matches;
    }

    public function activated() : bool
    {
        return $this->matches;
    }

    public function type() : string
    {
        return __CLASS__;
    }

    public function parameters() : array
    {
        return [self::PARAM_MATCHES => $this->matches];
    }


}