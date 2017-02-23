<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use PHPUnit\Framework\TestCase;

class ConversationTest extends TestCase
{
    public function testConversationCanBeCreatedWithState()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $states = new StateSet($initialState);
        $conversation = Conversation::create($states, new TransitionList(), $initialState);
        static::assertEquals($initialState, $conversation->state());
    }
    public function testConversationCanReturnStateList()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $states = new StateSet($initialState, ConversationState::createWithoutActions('state-1'), ConversationState::createWithoutActions('state-2'));
        $conversation = Conversation::create($states, new TransitionList(), $initialState);
        static::assertEquals($states, $conversation->states());
    }
    public function testConversationCannotBeCreatedWithUnknownState()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $states = new StateSet(ConversationState::createWithoutActions('only-something-else'));

        $this->expectException(\DomainException::class);
        Conversation::create($states, new TransitionList(), $initialState);
    }

    public function testConversationCanBeCreatedInUnstartedState()
    {
        $initialState = ConversationState::createWithEntryActions('initial', new ActionList($this->actionExpectedNotToBeCalled()));
        $states = new StateSet($initialState);
        $conversation = Conversation::createUnstarted($states, new TransitionList(), $initialState);
        static::assertInstanceOf(UnstartedState::class, $conversation->state());
    }

    public function testUnstartedConversationCanBeStarted()
    {
        $initialState = ConversationState::createWithEntryActions('initial', new ActionList($this->actionExpectedToBeCalled()));
        $states = new StateSet($initialState);
        $conversation = Conversation::createUnstarted($states, new TransitionList(), $initialState);
        static::assertSame($initialState, $conversation->continue()->state());
    }

    public function testUnstartedConversationCannotBeCreatedWithUnknownInitialState()
    {
        $initialState = ConversationState::createWithEntryActions('initial', new ActionList($this->actionExpectedNotToBeCalled()));
        $states = new StateSet(ConversationState::createWithoutActions('only-something-else'));

        $this->expectException(\DomainException::class);
        Conversation::createUnstarted($states, new TransitionList(), $initialState);
    }

    public function testTransitionBasedOnTriggers()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $alternateState = ConversationState::createWithoutActions('final');
        $unreachableState = ConversationState::createWithoutActions('unreachable');
        /** @var Conversation $conversation */
        $conversation = Conversation::create(
            new StateSet($initialState, $alternateState),
            new TransitionList(
                ConversationTransition::createAnonymous($initialState, $unreachableState, new TriggerList(new FixedTrigger(false))),
                ConversationTransition::createAnonymous($initialState, $alternateState, new TriggerList(new FixedTrigger(true))),
                ConversationTransition::createAnonymous($alternateState, $initialState, new TriggerList(new FixedTrigger(true)))
            ),
            $initialState
        );
        static::assertEquals($initialState, $conversation->state());
        $conversation = $conversation->continue();
        static::assertEquals($alternateState, $conversation->state());
        $conversation = $conversation->continue();
        static::assertEquals($initialState, $conversation->state());
    }
    public function testNewObjectIfStateChanged()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $nextState = ConversationState::createWithoutActions('final');
        $conversation = Conversation::create(
            new StateSet($initialState, $nextState),
            new TransitionList(
                ConversationTransition::createAnonymous($initialState, $nextState, new TriggerList(new FixedTrigger(true)))
            ),
            $initialState
        );
        static::assertNotSame($conversation, $conversation->continue(), 'continue() should return new object if state changed');
        static::assertEquals($initialState, $conversation->state(), 'Conversation should be immutable');
    }
    public function testNoNewObjectIfStateUnchanged()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $conversation = Conversation::create(
            new StateSet($initialState),
            new TransitionList(
            ),
            $initialState
        );
        static::assertSame($conversation, $conversation->continue(), 'continue() should return new object if state changed');
    }
    public function testEntryAndExitActions()
    {
        $initialState = ConversationState::createWithExitActions('initial', new ActionList($this->actionExpectedToBeCalled()));
        $finalState = ConversationState::createWithEntryActions('final', new ActionList($this->actionExpectedToBeCalled()));
        $conversation = Conversation::create(
            new StateSet($initialState, $finalState),
            new TransitionList(
                ConversationTransition::createAnonymous($initialState, $finalState, new TriggerList(new FixedTrigger(true)))
            ),
            $initialState
        );
        $conversation->continue()->continue();
    }

    /**
     * @return CallbackAction
     */
    private function actionExpectedToBeCalled() : CallbackAction
    {
        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();
        $callbackMock->expects(static::once())->method('__invoke');
        return new CallbackAction($callbackMock);
    }

    /**
     * @return CallbackAction
     */
    private function actionExpectedNotToBeCalled() : CallbackAction
    {
        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();
        $callbackMock->expects(static::never())->method('__invoke');
        return new CallbackAction($callbackMock);
    }
}
