<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use FireGento\MageBot\StateMachine\ConversationContext;
use FireGento\MageBot\StateMachine\Trigger;

/**
 * Trigger for selected answer of multiple choice question {@see QuestionAction}
 */
class AnswerTrigger implements Trigger
{
    const PARAM_VALUE = 'value';

    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function activated(ConversationContext $context) : bool
    {
        //TODO find out if and in which cases Answer::getValue() should be used instead
        return $context instanceof BotmanConversationContext && $context->getAnswer()->__toString() === $this->value;
    }

    public function type() : string
    {
        return __CLASS__;
    }

    public function parameters() : array
    {
        return [
            self::PARAM_VALUE => $this->value
        ];
    }

}