<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use PHPUnit\Framework\TestCase;

class StateSetTest extends TestCase
{
    public function testStateSetIsUnique()
    {
        $stateA = ConversationState::createWithoutActions('A');
        $stateB = ConversationState::createWithoutActions('B');
        $stateSet = new StateSet($stateA, $stateA, $stateB);
        static::assertEquals(
            [$stateA, $stateB],
            $stateSet->getArrayCopy(),
            '', 0.0, 10, true
        );
    }
    public function testStateSetCanBeExtended()
    {
        $stateA = ConversationState::createWithoutActions('A');
        $stateB = ConversationState::createWithoutActions('B');
        $stateSet = new StateSet($stateA);
        static::assertEquals(
            [$stateA, $stateB],
            \iterator_to_array($stateSet->with($stateB)),
            '', 0.0, 10, true
        );
        static::assertEquals(
            [$stateA],
            $stateSet->getArrayCopy()
        );
    }
}
