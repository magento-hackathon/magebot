<?php
declare(strict_types=1);

namespace FireGento\MageBot\Botman;

use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\Button;
use Mpociot\BotMan\Question;
use PHPUnit\Framework\TestCase;

class QuestionActionTest extends TestCase
{
    public function testDefinition()
    {
        $message = 'Yes or no?';
        $answers = [
            ['text' => 'yes', 'value' => 1],
            ['text' => 'no', 'value' => 0],
        ];
        $action = new QuestionAction(
            $this->createMock(BotMan::class),
            $message, $answers
        );
        static::assertEquals(QuestionAction::class, $action->type());
        static::assertEquals(
            [
                'message' => $message,
                'answers' => $answers,
            ],
            $action->parameters()
        );
    }

    /**
     * @dataProvider dataInvalidDefinitions
     */
    public function testInvalidDefinition(array $answers)
    {
        $this->expectException(\DomainException::class);
        new QuestionAction(
            $this->createMock(BotMan::class),
            'what?',
            $answers
        );
    }

    public static function dataInvalidDefinitions()
    {
        return [
            'answer is not array' => [
                ['answer!']
            ],
            'answer does not contain text' => [
                [['value' => 'only-value']]
            ],
            'answer does not contain value' => [
                [['text' => 'only text']]
            ],
        ];
    }

    public function testSendMessage()
    {
        $message = 'Top or flop?';
        $answers = [
            ['text' => 'top', 'value' => 1, 'image_url' => 'hop.png'],
            ['text' => 'flop', 'value' => -1, 'image_url' => 'flop.gif'],
            ['text' => 'meh', 'value' => 0]
        ];

        $botMock = $this->createMock(BotMan::class);
        $botMock->expects(static::once())
            ->method('reply')
            ->willReturnCallback(function(Question $question) use ($message, $answers) {
                static::assertEquals($message, $question->getText());
                $buttons = $question->getButtons();
                static::assertCount(\count($answers), $buttons);
                reset($buttons);
                foreach ($answers as $expectedAnswer) {
                    static::assertArraySubset($expectedAnswer, current($buttons));
                    next($buttons);
                }
            });

        $action = new QuestionAction(
            $botMock,
            $message, $answers
        );
        $action->execute();
    }
}