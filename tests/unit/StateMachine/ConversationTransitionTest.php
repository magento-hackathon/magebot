<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use FireGento\MageBot\StateMachine\Serialization\NewTriggerFactory;
use FireGento\MageBot\StateMachine\Serialization\SerializableTrigger;
use PHPUnit\Framework\TestCase;
use const true;

class ConversationTransitionTest extends TestCase
{
    protected function setUp()
    {
        SerializableTrigger::setTriggerFactory(new NewTriggerFactory());
    }

    protected function tearDown()
    {
        SerializableTrigger::resetTriggerFactory();
    }

    public function testCanBeSerializedWithTriggers()
    {
        $stateFrom = ConversationState::createWithoutActions('state-from');
        $stateTo = ConversationState::createWithoutActions('state-to');
        $transition = ConversationTransition::createAnonymous(
            $stateFrom,
            $stateTo,
            new TriggerList(
                $this->notSerializableTrigger(false),
                $this->notSerializableTrigger(true)
            )
        );
        $serializedTransition = serialize($transition);
        /** @var ConversationTransition $unserializedTransition */
        $unserializedTransition = unserialize($serializedTransition);

        static::assertTrue($unserializedTransition->triggeredAt($stateFrom));

    }

    private function notSerializableTrigger($activated) : Trigger
    {
        $trigger = new FakeTrigger(['activated' => $activated]);
        $trigger->evilProperty = new class
        {
            // in case that serialization of anonymous classes is allowed in future PHP versions:
            public function __sleep()
            {
                throw new \Exception('I should not be serialized');
            }
        };
        return $trigger;
    }
}
