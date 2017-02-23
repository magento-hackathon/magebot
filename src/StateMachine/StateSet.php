<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use function array_unique as unique;
use function array_values as values;
use const SORT_REGULAR as DO_NOT_CHANGE_TYPES;

final class StateSet extends \ArrayIterator implements States
{
    public function __construct(State ...$states)
    {
        parent::__construct(values(unique($states, DO_NOT_CHANGE_TYPES)));
    }

    public function current() : State
    {
        return parent::current();
    }

    public function contains(State $state) : bool
    {
        return \in_array($state, $this->getArrayCopy(), true);
    }

    public function with(State $state) : States
    {
        return new static($state, ...$this->getArrayCopy());
    }

}