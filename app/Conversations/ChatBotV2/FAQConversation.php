<?php

namespace App\Conversations\ChatBotV2;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class FAQConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $extras = $this->bot->getMessage()->getExtras();
        \Log::debug("Extras: " . print_r($extras, true));
        $this->say($extras['apiReply']);
        $this->getUserFeedback();
    }

    public function getUserFeedback()
    {
        $this->ask('Was that information helpful to you? ', [
            [
                'pattern' => 'yes|yep',
                'callback' => function () {
                    $this->say('Great!!! :) Anything else?');
                }
            ],
            [
                'pattern' => 'nah|no|nope',
                'callback' => function () {
                    $this->say('Ummm... :( Can you re-phrase your question?');
                }
            ]
        ]);
    }
}
