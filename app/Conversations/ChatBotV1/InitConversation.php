<?php

namespace App\Conversations\ChatBotV1;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class InitConversation extends Conversation
{
    protected $name;

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->name = $this->bot->userStorage()->get('userName');

        if (!$this->name) {
            $this->askName();
        } else {
            $this->askChoice();
        }
    }

    public function askName()
    {
        $this->ask('Hello! What is your name?', function (Answer $answer) {
            if (trim($answer->getText())) {
                $this->name = $answer->getText();
                $this->bot->userStorage()->save([
                    'userName' => $this->name
                ]);
                $this->askChoice();
            } else {
                $this->repeat();
            }
        });
    }

    public function askChoice()
    {
        $options = [
            'joke' => 'Tell a joke',
            'quote' => 'Give me a fancy quote',
            'ask' => 'Ask me a question',
        ];

        $question = $this->createQuestion('Hello,' . $this->name . '! What do you want me to do?', $options, 'ask_choice');

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'joke':
                        $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                        $this->say($joke->value->joke);
                        break;
                    case 'quote':
                        $quote = Inspiring::quote();
                        $this->say($quote);
                        break;
                    case 'ask':
                        $this->bot->startConversation(new QuizConversation);
                        break;
                }
            } else {
                $this->say('I didn\'t understand that. Please select from the following options.');
                $this->repeat();
            }
        });
    }

    protected function createButtons($options)
    {
        $buttons = [];
        foreach ($options as $key => $option) {
            if ($option) {
                $buttons[] = Button::create($option)->value($key);
            }
        }
        return $buttons;
    }

    protected function createQuestion(string $questionText, array $options, string $callbackId)
    {
        $buttons = $this->createButtons($options);

        return Question::create($questionText)
            ->fallback('Unable to ask question')
            ->callbackId($callbackId)
            ->addButtons($buttons);
    }
}
