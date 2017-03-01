<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;
use FireGento\MageBot\StateMachine\ConversationContext;
use Mpociot\BotMan\Answer;

/**
 * Context of conversation: current user answer from botman, custom variables etc.
 */
class BotmanConversationContext implements ConversationContext
{
    /** @var array */
    private $persistentVars = [];

    /** @var Answer */
    private $answer;

    public function getAnswer() : Answer
    {
        return $this->answer;
    }
    public function setAnswer(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function setPersistentVar(string $key, $value)
    {
        $this->persistentVars[$key] = $value;
    }

    public function hasPersistentVar(string $key) : bool
    {
        return array_key_exists($key, $this->persistentVars);
    }

    public function getPersistentVar(string $key)
    {
        if (! $this->hasPersistentVar($key)) {
            throw new \RuntimeException("Persistent variable {$key} is not set.");
        }
        return $this->persistentVars[$key];
    }

    public function serialize()
    {
        return json_encode($this->persistentVars);
    }

    public function unserialize($serialized)
    {
        $this->persistentVars = json_decode($serialized, true);
    }

}