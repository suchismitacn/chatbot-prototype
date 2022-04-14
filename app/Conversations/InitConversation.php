<?php

namespace App\Conversations;

use App\Repositories\ConversationRepository;
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
        $this->storeConversation('bot', $question->getText(), $options);

        return $this->ask($question, function (Answer $answer) use ($options) {
            if ($answer->isInteractiveMessageReply()) {
                $this->storeConversation('user', $options[$answer->getText()]);
                switch ($answer->getValue()) {
                    case 'joke':
                        $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'));
                        $this->say($joke->value->joke);
                        $this->storeConversation('bot', $joke->value->joke);
                        break;
                    case 'quote':
                        $quote = Inspiring::quote();
                        $this->say($quote);
                        $this->storeConversation('bot', $quote);
                        break;
                    case 'ask':
                        $this->bot->startConversation(new QuizConversation);
                        break;
                    default:
                        $this->say('Ok! Have a nice day. :)');
                        $this->storeConversation('bot', 'Ok! Have a nice day. :)');
                }
            } else {
                $this->storeConversation('user', $answer->getText());
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

    protected function storeConversation(string $sender, string $text, array $options = null)
    {
        return (new ConversationRepository)->storeConversation(request()->session()->getId(), $sender, $text, $options);
    }
}
