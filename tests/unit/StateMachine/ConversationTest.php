<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use PHPUnit\Framework\TestCase;

class ConversationTest extends TestCase
{
    public function testConversationStartsInInitialState()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $states = new StateSet($initialState);
        $conversation = Conversation::create($states, new TransitionList(), $initialState);
        static::assertInstanceOf(Conversation::class, $conversation);
        static::assertEquals($initialState, $conversation->state());
    }
    public function testConversationCanBeInitializedInAnyState()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $restoredState = ConversationState::createWithoutActions('any');
        $states = new StateSet($initialState, $restoredState);
        $conversation = Conversation::createWithState($states, new TransitionList(), $initialState, $restoredState);
        static::assertInstanceOf(Conversation::class, $conversation);
        static::assertEquals($restoredState, $conversation->state());
    }
    public function testConversationCanReturnStateList()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $states = new StateSet($initialState, ConversationState::createWithoutActions('state-1'), ConversationState::createWithoutActions('state-2'));
        $conversation = Conversation::create($states, new TransitionList(), $initialState);
        static::assertEquals($states, $conversation->states());
    }
    public function testConversationCannotBeCreatedWithUnknownInitialState()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $states = new StateSet(ConversationState::createWithoutActions('only-something-else'));

        $this->expectException(\DomainException::class);
        Conversation::create($states, new TransitionList(), $initialState);
    }
    public function testConversationCannotBeCreatedWithUnknownCurrentState()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $states = new StateSet($initialState);

        $this->expectException(\DomainException::class);
        Conversation::createWithState($states, new TransitionList(), $initialState, ConversationState::createWithoutActions('unknown-current-state'));
    }
    public function testTransitionBasedOnTriggers()
    {
        $initialState = ConversationState::createWithoutActions('initial');
        $alternateState = ConversationState::createWithoutActions('final');
        $unreachableState = ConversationState::createWithoutActions('unreachable');
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
        $conversation->continue();
        static::assertEquals($alternateState, $conversation->state());
        $conversation->continue();
        static::assertEquals($initialState, $conversation->state());
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
        $conversation->continue();
        $conversation->continue();
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
    }}
