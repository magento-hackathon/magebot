<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;


use FireGento\MageBot\StateMachine\Serialization\ActionFactory;
use FireGento\MageBot\StateMachine\Serialization\SerializableAction;
use PHPUnit\Framework\TestCase;

class SerializableActionTest extends TestCase
{
    /**
     * @var ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $actionFactoryMock;

    protected function setUp()
    {
        $this->actionFactoryMock = $this->getMockBuilder(ActionFactory::class)
            ->getMockForAbstractClass();
        SerializableAction::setActionFactory($this->actionFactoryMock);
    }
    protected function tearDown()
    {
        SerializableAction::resetActionFactory();
    }

    public function testRecreateSerializedAction()
    {
        $parameters = ['foo' => 'bar'];
        $action = new FakeAction($parameters);
        $this->actionFactoryMock->expects(static::once())
            ->method('create')
            ->with(FakeAction::class, $parameters)
            ->willReturn($action);

        $serializedAction = serialize(new SerializableAction($action));
        /** @var SerializableAction $unserializedAction */
        $unserializedAction = unserialize($serializedAction);

        static::assertEquals($parameters, $unserializedAction->parameters());
        static::assertEquals(0, $action->timesExecuted());
        $unserializedAction->execute($this->createMock(ConversationContext::class));
        static::assertEquals(1, $action->timesExecuted());
    }

    public function testPreventMultipleDecoration()
    {
        $parameters = ['boo' => 'far'];
        $action = new FakeAction($parameters);
        $this->actionFactoryMock->expects(static::once())
            ->method('create')
            ->with(FakeAction::class, $parameters)
            ->willReturn($action);

        $serialized = serialize(new SerializableAction(new SerializableAction($action)));
        /** @var SerializableAction $deserialized */
        $deserialized = unserialize($serialized);
        static::assertEquals($parameters, $deserialized->parameters());

        static::assertEquals($parameters, $deserialized->parameters());
        static::assertEquals(0, $action->timesExecuted());
        $deserialized->execute($this->createMock(ConversationContext::class));
        static::assertEquals(1, $action->timesExecuted());
    }
}
