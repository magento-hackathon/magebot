<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use PHPUnit\Framework\TestCase;

class TransitionListTest extends TestCase
{
    public function testTransitionListCanBeExtended()
    {
        $stateA = ConversationState::createWithoutActions('A');
        $stateB = ConversationState::createWithoutActions('B');
        $transitionAtoB = ConversationTransition::createAnonymous($stateA, $stateB, new TriggerList());
        $transitionBtoA = ConversationTransition::createAnonymous($stateB, $stateA, new TriggerList());
        $transitions = new TransitionList($transitionAtoB);

        static::assertEquals(
            [$transitionBtoA, $transitionAtoB],
            \iterator_to_array($transitions->prepend($transitionBtoA))
        );
        static::assertEquals(
            [$transitionAtoB],
            $transitions->getArrayCopy()
        );

    }
}
