<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use function array_unique as unique;
use function array_values as values;

final class StateSet extends \ArrayIterator implements States
{
    public function __construct(State ...$states)
    {
        parent::__construct(values(unique($states, SORT_REGULAR)));
    }

    public function current() : State
    {
        return parent::current();
    }

    public function contains(State $state) : bool
    {
        return \in_array($state, $this->getArrayCopy(), true);
    }

}