<?php

namespace App\Conversations\ChatBotV1;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SendConversation extends Conversation
{
    protected $email;

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askEmail();
    }

    public function askEmail()
    {
        $this->ask('Sure thing - what is your email?', function(Answer $answer) {
            if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                $this->email = $answer->getText();
                $this->say('Great - that is all we need, sending you the mail to: ' . $this->email);
                $this->handleMail();
                $this->say('Mail sent to you! Please check your inbox.');
            } else {
                $this->repeat('That doesn\'t look like a valid email. What is your email?');
            }

        });
    }

    protected function handleMail()
    {
        $sessionId = request()->session()->getId();
        return (new ConversationRepository)->sendConversation($sessionId, $this->email);
    }
}
