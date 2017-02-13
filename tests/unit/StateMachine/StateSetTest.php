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
            $stateSet->getArrayCopy()
        );
    }
}
