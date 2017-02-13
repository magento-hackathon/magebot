<?php
declare(strict_types=1);

namespace FireGento\MageBot\StateMachine;

use PHPUnit\Framework\TestCase;

class ConversationStateTest extends TestCase
{
    public function testCreateWithoutActions()
    {
        $state = ConversationState::createWithoutActions('empty');
        static::assertEquals('empty', $state->name());
        static::assertEquals(new ActionList, $state->entryActions());
        static::assertEquals(new ActionList, $state->exitActions());
    }
    public function testCreateWithActions()
    {
        $entryActions = $this->dummyActionList();
        $exitActions = $this->dummyActionList();
        $state = ConversationState::createWithActions('both', $entryActions, $exitActions);
        static::assertEquals('both', $state->name());
        static::assertSame($entryActions, $state->entryActions());
        static::assertSame($exitActions, $state->exitActions());
    }
    public function testCreateWithEntryActions()
    {
        $entryActions = $this->dummyActionList();
        $state = ConversationState::createWithEntryActions('entry', $entryActions);
        static::assertEquals('entry', $state->name());
        static::assertSame($entryActions, $state->entryActions());
        static::assertEquals(new ActionList, $state->exitActions());
    }
    public function testCreateWithExitActions()
    {
        $exitActions = $this->dummyActionList();
        $state = ConversationState::createWithExitActions('exit', $exitActions);
        static::assertEquals('exit', $state->name());
        static::assertEquals(new ActionList, $state->entryActions());
        static::assertSame($exitActions, $state->exitActions());
    }

    /**
     * @return ActionList
     */
    private function dummyActionList() : ActionList
    {
        static $counter = 0;
        ++$counter;
        return new ActionList(
            new CallbackAction(
                function () use ($counter) {
                    return $counter;
                }
            )
        );
    }
}