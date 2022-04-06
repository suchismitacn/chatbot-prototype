<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class InitConversation extends Conversation
{
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }

    /**
     * First question
     */
    public function askReason()
    {
        $options = [
            'joke' => 'Tell a joke',
            'quote' => 'Give me a fancy quote',
            'ask' => 'Ask me a question',
            'bye' => 'Umm, gotta go.'
        ];

        $question = $this->createQuestion('Huh - you woke me up. What do you need?', $options, 'ask_reason');

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'joke':
                        $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                        $this->say($joke->value->joke);
                        break;
                    case 'quote':
                        $this->say(Inspiring::quote());
                        break;
                    case 'ask':
                        $this->bot->startConversation(new QuizConversation);
                        break;
                    default:
                        $this->say('Ok! Have a nice day. :)');
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
