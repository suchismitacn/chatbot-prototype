<?php

namespace App\Conversations\ChatBotV2;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class DepartmentRelatedConversation extends Conversation
{
    protected $department;
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->department = $this->bot->userStorage()->get('department');
        $this->askQuery();
    }

    public function askQuery()
    {
        $this->ask('Got it! What\'s your query?', function (Answer $answer) {
            if (trim($answer->getText())) {
                $this->say('Gimme a moment while I find what works best for your query!');
            } else {
                $this->repeat();
            }
        });
    }
}
