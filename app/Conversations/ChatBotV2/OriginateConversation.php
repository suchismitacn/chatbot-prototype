<?php

namespace App\Conversations\ChatBotV2;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

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
            $this->bot->startConversation(new MenuConversation);
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
                $this->bot->startConversation(new MenuConversation);
            } else {
                $this->repeat();
            }
        });
    }
}
