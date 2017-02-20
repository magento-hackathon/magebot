<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;


use FireGento\MageBot\StateMachine\Serialization\SerializableTrigger;
use FireGento\MageBot\StateMachine\Serialization\TriggerFactory;
use PHPUnit\Framework\TestCase;

class SerializableTriggerTest extends TestCase
{
    /**
     * @var \FireGento\MageBot\StateMachine\Serialization\TriggerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $triggerFactoryMock;

    protected function setUp()
    {
        $this->triggerFactoryMock = $this->getMockBuilder(TriggerFactory::class)
            ->getMockForAbstractClass();
        SerializableTrigger::setTriggerFactory($this->triggerFactoryMock);
    }
    protected function tearDown()
    {
        SerializableTrigger::resetTriggerFactory();
    }

    public function testRecreateSerializedTrigger()
    {
        $parameters = ['foo' => 'bar', 'activated' => false];
        $trigger = new FakeTrigger($parameters);
        $this->triggerFactoryMock->expects(static::once())
            ->method('create')
            ->with(FakeTrigger::class, $parameters)
            ->willReturn($trigger);

        $serializedTrigger = serialize(new SerializableTrigger($trigger));
        /** @var SerializableTrigger $unserializedTrigger */
        $unserializedTrigger = unserialize($serializedTrigger);

        static::assertEquals($parameters, $unserializedTrigger->parameters());
        static::assertFalse($unserializedTrigger->activated());
    }

    public function testPreventMultipleDecoration()
    {
        $parameters = ['boo' => 'far', 'activated' => true];
        $trigger = new FakeTrigger($parameters);
        $this->triggerFactoryMock->expects(static::once())
            ->method('create')
            ->with(FakeTrigger::class, $parameters)
            ->willReturn($trigger);

        $serialized = serialize(new SerializableTrigger(new SerializableTrigger($trigger)));
        /** @var SerializableTrigger $unserializedTrigger */
        $unserializedTrigger = unserialize($serialized);
        static::assertEquals($parameters, $unserializedTrigger->parameters());
        static::assertTrue($unserializedTrigger->activated());
    }
}
