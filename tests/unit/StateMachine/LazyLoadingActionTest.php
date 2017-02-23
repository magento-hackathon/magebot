<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use FireGento\MageBot\StateMachine\Serialization\ActionFactory;
use FireGento\MageBot\StateMachine\Serialization\LazyLoadingAction;
use PHPUnit\Framework\TestCase;

class LazyLoadingActionTest extends TestCase
{
    /**
     * @var ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $actionFactoryMock;

    protected function setUp()
    {
        $this->actionFactoryMock = $this->getMockBuilder(ActionFactory::class)
            ->getMockForAbstractClass();
    }

    public function testLazyLoad()
    {
        $parameters = ['foo' => 'bar'];

        $lazyLoadingAction = new LazyLoadingAction($this->actionFactoryMock, FakeAction::class, $parameters);

        $loadedAction = new FakeAction($parameters);
        $this->actionFactoryMock->expects(static::once())
            ->method('create')
            ->with(FakeAction::class, $parameters)
            ->willReturn($loadedAction);

        static::assertEquals(FakeAction::class, $lazyLoadingAction->type());
        static::assertEquals($parameters, $lazyLoadingAction->parameters());
        $lazyLoadingAction->execute();
        $lazyLoadingAction->execute();

        static::assertEquals(2, $loadedAction->timesExecuted());

    }
}