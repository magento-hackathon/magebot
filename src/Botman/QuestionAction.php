<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\ConversationContext;
use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\Question;

/**
 * Action for multiple choice questions
 */
class QuestionAction implements BotmanAction
{
    const PARAM_ANSWERS = 'answers';
    const PARAM_MESSAGE = 'message';
    const ANSWER_PARAM_TEXT = 'text';
    const ANSWER_PARAM_VALUE = 'value';
    const ANSWER_PARAM_IMAGE_URL = 'image_url';

    /**
     * @var BotMan
     */
    private $botman;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $answers;

    public function __construct(BotMan $botman, string $message, array $answers)
    {
        foreach ($answers as $answer) {
            self::validateAnswer($answer);
        }
        $this->botman = $botman;
        $this->message = $message;
        $this->answers = $answers;
    }

    private static function validateAnswer($answer)
    {
        if (! is_array($answer)) {
            throw new \DomainException('Answer definition must be array');
        }
        if (! array_key_exists(self::ANSWER_PARAM_TEXT, $answer)) {
            throw new \DomainException('Answer definition must contain "text"');
        }
        if (! array_key_exists(self::ANSWER_PARAM_VALUE, $answer)) {
            throw new \DomainException('Answer definition must contain "value"');
        }
    }

    public function type() : string
    {
        return __CLASS__;
    }

    public function parameters() : array
    {
        return [
            self::PARAM_MESSAGE => $this->message,
            self::PARAM_ANSWERS => $this->answers,
        ];
    }

    public function execute(ConversationContext $context)
    {
        $this->botman->reply(
            Question::create($this->message)->addButtons(
                array_map(
                    function(array $answer) : Button {
                        return Button::create($answer[QuestionAction::ANSWER_PARAM_TEXT])
                            ->value($answer[QuestionAction::ANSWER_PARAM_VALUE])
                            ->image($answer[QuestionAction::ANSWER_PARAM_IMAGE_URL] ?? '');
                    },
                    $this->answers
                )
            )
        );
    }

}