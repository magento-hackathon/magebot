<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\Action;
use FireGento\MageBot\StateMachine\ActionList;
use FireGento\MageBot\StateMachine\Conversation;
use FireGento\MageBot\StateMachine\ConversationState;
use FireGento\MageBot\StateMachine\ConversationTransition;
use FireGento\MageBot\StateMachine\Serialization\ActionFactory;
use FireGento\MageBot\StateMachine\Serialization\FakeActionFactory;
use FireGento\MageBot\StateMachine\Serialization\FakeTriggerFactory;
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
    private $username;
    private $channel;
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
        $conversationDefinitions = new ConversationDefinitionList(
            $this->mittagessenConversationDefinition($this->botman)
        );
        $conversationDefinitions->register($this->botman);

        SerializableAction::setActionFactory(new BotmanActionFactory($this->botman));
        SerializableTrigger::setTriggerFactory(new NewTriggerFactory);
    }

    protected function tearDown()
    {
        ProxyDriver::setInstance(new NullDriver(new Request, [], new Curl));
        SerializableAction::resetActionFactory();
        SerializableTrigger::resetTriggerFactory();
    }

    public function testConversationWithQuestionAndAnswer()
    {
        $this->username = 'user123';
        $this->channel = '#mittagessen';

        $this->userWrites('Mittagessen');
        $this->botman->listen();
        $this->assertBotReplies(
            [
                'Mittagessenplan',
                Question::create('Ihr Kind isst')->addButtons(
                    [
                        Button::create('Fisch')->value('fisch'),
                        Button::create('Fleisch')->value('fleisch')
                    ]
                )
            ]
        );
        $this->userWrites('fisch');
        $this->assertBotReplies(['Ihr Kind isst kein Fleisch']);
    }

    private function mittagessenConversationDefinition(BotMan $botman) : ConversationDefinition
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
        return new class($conversation) implements ConversationDefinition {
            /** @var Conversation */
            private $conversation;

            public function __construct(Conversation $conversation)
            {
                $this->conversation = $conversation;
            }

            public function patternToStart() : string
            {
                return 'Mittagessen';
            }

            public function create() : Conversation
            {
                return $this->conversation;
            }
        };
    }

    private function userWrites(string $text)
    {
        $this->fakeDriver->resetBotMessages();
        $this->fakeDriver->messages = [new Message($text, $this->username, $this->channel)];
        $this->botman->loadActiveConversation();
    }

    private function assertBotReplies($replies)
    {
        static::assertEquals($replies, $this->fakeDriver->getBotMessages());
    }
}