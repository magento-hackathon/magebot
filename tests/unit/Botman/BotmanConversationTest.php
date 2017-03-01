<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\Action;
use FireGento\MageBot\StateMachine\ActionList;
use FireGento\MageBot\StateMachine\Conversation;
use FireGento\MageBot\StateMachine\ConversationState;
use FireGento\MageBot\StateMachine\ConversationTransition;
use FireGento\MageBot\StateMachine\Serialization\ActionFactory;
use FireGento\MageBot\StateMachine\Serialization\NewActionFactory;
use FireGento\MageBot\StateMachine\Serialization\NewTriggerFactory;
use FireGento\MageBot\StateMachine\Serialization\SerializableAction;
use FireGento\MageBot\StateMachine\Serialization\SerializableTrigger;
use FireGento\MageBot\StateMachine\Serialization\TriggerFactory;
use FireGento\MageBot\StateMachine\StateSet;
use FireGento\MageBot\StateMachine\TransitionList;
use FireGento\MageBot\StateMachine\Trigger;
use FireGento\MageBot\StateMachine\TriggerList;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\BotManFactory;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\DriverManager;
use Mpociot\BotMan\Drivers\NullDriver;
use Mpociot\BotMan\Http\Curl;
use Mpociot\BotMan\Interfaces\DriverInterface;
use Mpociot\BotMan\Message;
use Mpociot\BotMan\Question;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Integration test for Botman
 */
class BotmanConversationTest extends TestCase
{
    /** @var BotMan */
    private $botman;
    /** @var FakeDriver */
    private $fakeDriver;

    public static function setUpBeforeClass()
    {
        DriverManager::loadDriver(ProxyDriver::class);
    }

    protected function setUp()
    {
        $this->fakeDriver = new FakeDriver();
        ProxyDriver::setInstance($this->fakeDriver);
        $this->botman = BotManFactory::create([]);

        //TODO implement real Botman{Trigger|Action}Factory classes
        SerializableAction::setActionFactory(new class($this->botman) implements ActionFactory {
            private $botman;
            public function __construct(BotMan $botman)
            {
                $this->botman = $botman;
            }

            public function create(string $type, array $parameters) : Action
            {
                return new $type($this->botman, ...array_values($parameters));
            }
        });
        SerializableTrigger::setTriggerFactory(new class implements TriggerFactory {
            public function create(string $type, array $parameters) : Trigger
            {
                return new $type(...array_values($parameters));
            }
        });
    }

    protected function tearDown()
    {
        ProxyDriver::setInstance(new NullDriver(new Request, [], new Curl));
        SerializableAction::resetActionFactory();
        SerializableTrigger::resetTriggerFactory();
    }

    public function testConversationWithQuestionAndAnswer()
    {
        $this->fakeDriver->messages = [ new Message('Hallo', 'user123', '#mittagessen') ];
        $this->botman->hears('Hallo', function(BotMan $botman) {
            $botman->startConversation(new BotmanConversation($botman, $this->defineConversation($botman)));
        });
        $this->botman->listen();

        static::assertCount(2, $this->fakeDriver->getBotMessages());
        static::assertEquals(
            'Mittagessenplan',
            $this->fakeDriver->getBotMessages()[0]
        );
        static::assertEquals(
            Question::create('Ihr Kind isst')->addButtons(
                [
                    Button::create('Fisch')->value('fisch'),
                    Button::create('Fleisch')->value('fleisch')
                ]
            ),
            $this->fakeDriver->getBotMessages()[1]
        );

        $this->fakeDriver->messages = [ new Message('fisch', 'user123', '#mittagessen') ];
        $this->botman->loadActiveConversation();
        static::assertCount(3, $this->fakeDriver->getBotMessages());
        static::assertEquals('Ihr Kind isst kein Fleisch', $this->fakeDriver->getBotMessages()[2]);
    }

    private function defineConversation(BotMan $botman) : Conversation
    {
        $initialState = ConversationState::createWithEntryActions(
            'state-fisch', new ActionList(
                new MessageAction($botman, 'Mittagessenplan'),
                new QuestionAction($botman, 'Ihr Kind isst', [['text' => 'Fisch', 'value' => 'fisch'], ['text' => 'Fleisch', 'value' => 'fleisch']])
            )
        );
        $fischState = ConversationState::createWithEntryActions(
            'state-fisch',
            new ActionList(new MessageAction($botman, 'Ihr Kind isst kein Fleisch'))
        );
        $fleischState = ConversationState::createWithEntryActions(
            'state-fleisch',
            new ActionList(new MessageAction($botman, 'Ihr Kind isst kein Fisch'))
        );
        $conversation = Conversation::createUnstarted(
            new StateSet($initialState, $fischState, $fleischState),
            new TransitionList(
                ConversationTransition::createAnonymous(
                    $initialState, $fischState, new TriggerList(
                        new AnswerTrigger('fisch')
                    )
                ),
                ConversationTransition::createAnonymous(
                    $initialState, $fleischState, new TriggerList(
                        new AnswerTrigger('fleisch')
                    )
                )
            ),
            $initialState, new BotmanConversationContext()
        );
        return $conversation;
    }
}