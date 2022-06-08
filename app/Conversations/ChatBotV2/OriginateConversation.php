<?php

namespace App\Conversations\ChatBotV2;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class OriginateConversation extends Conversation
{
    protected $name;

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->name = $this->bot->userStorage()->get('name');

        if (!$this->name) {
            $this->askName();
        } else {
            $this->askDepartment();
        }
    }

    public function askName()
    {
        $this->ask('Hello! What is your name?', function (Answer $answer) {
            if (trim($answer->getText())) {
                $this->name = $answer->getText();
                $this->bot->userStorage()->save([
                    'name' => $this->name
                ]);
                $this->askDepartment();
            } else {
                $this->repeat();
            }
        });
    }

    public function askDepartment()
    {
        $options = [
            'sales' => 'Sales',
            'service' => 'Service',
            'finance' => 'Finance',
            'valuation' => 'Valuation',
            'general' => 'General'
        ];

        $question = $this->createQuestion('Hello, ' . $this->name . '! Your query is related to which department? If you are not sure, select general.', $options, 'ask_department');

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getText() === 'general') {
                    $this->listGeneralOptions();
                } else {
                    $this->bot->userStorage()->save([
                        'department' => $answer->getText()
                    ]);
                    $this->bot->startConversation(new DepartmentRelatedConversation());
                }
            } else {
                $this->say('I didn\'t understand that. Please select from the following options.');
                $this->repeat();
            }
        });
    }

    public function listGeneralOptions()
    {
        $options = [
            'dealerships' => 'Nearest dealerships',
            'contacts' => 'Show useful contacts',
            'popular' => 'Popular FAQs',
        ];

        $question = $this->createQuestion('What can I help you with?', $options, 'list_general_options');

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->say('You choose: ' . $answer->getText());
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
