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

        static::assertTrue($unserializedTransition->triggeredAt($stateFrom, $this->createMock(ConversationContext::class)));
    }

    public function testToArray()
    {
        $stateFrom = ConversationState::createWithoutActions('denmark');
        $stateTo = ConversationState::createWithoutActions('germany');
        $transition = ConversationTransition::create(
            'shopping tour',
            $stateFrom,
            $stateTo,
            new TriggerList(
                new FakeTrigger(['alcohol' => 0])
            )
        );
        $transitionAsArray = $transition->toArray();
        static::assertEquals(
            ['name', 'source', 'target', 'triggers'],
            array_keys($transitionAsArray)
        );
        static::assertEquals('shopping tour', $transitionAsArray['name']);
        static::assertSame($stateFrom, $transitionAsArray['source']);
        static::assertSame($stateTo, $transitionAsArray['target']);
        static::assertJsonStringEqualsJsonString(
            json_encode([
                ['type' => FakeTrigger::class, 'parameters' => ['alcohol' => 0]]
            ]),
            $transitionAsArray['triggers']
        );
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
