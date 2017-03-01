<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use FireGento\MageBot\StateMachine\Serialization\TriggerFactory;
use FireGento\MageBot\StateMachine\Serialization\LazyLoadingTrigger;
use PHPUnit\Framework\TestCase;

class LazyLoadingTriggerTest extends TestCase
{
    /**
     * @var TriggerFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $triggerFactoryMock;

    protected function setUp()
    {
        $this->triggerFactoryMock = $this->getMockBuilder(TriggerFactory::class)
            ->getMockForAbstractClass();
    }

    public function testLazyLoad()
    {
        $type = 'MockTrigger';
        $parameters = ['foo' => 'bar'];

        $lazyLoadingTrigger = new LazyLoadingTrigger($this->triggerFactoryMock, $type, $parameters);

        $loadedTrigger = $this->getMockBuilder(Trigger::class)
            ->getMock();
        $loadedTrigger->method('type')->willReturn($type);
        $loadedTrigger->method('parameters')->willReturn($parameters);
        $loadedTrigger->expects(static::exactly(2))
            ->method('activated')
            ->willReturn(false);
        $this->triggerFactoryMock->expects(static::once())
            ->method('create')
            ->with($type, $parameters)
            ->willReturn($loadedTrigger);

        static::assertEquals($type, $lazyLoadingTrigger->type());
        static::assertEquals($parameters, $lazyLoadingTrigger->parameters());
        static::assertFalse($lazyLoadingTrigger->activated($this->createMock(ConversationContext::class)));
        static::assertFalse($lazyLoadingTrigger->activated($this->createMock(ConversationContext::class)));

    }
}