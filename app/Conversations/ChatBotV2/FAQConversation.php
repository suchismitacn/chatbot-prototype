<?php

namespace App\Conversations\ChatBotV2;

use BotMan\BotMan\Messages\Conversations\Conversation;

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
        // \Log::debug("Extras: " . print_r($extras, true));
        $this->say($extras['apiReply']);
        $this->getUserFeedback();
    }

    public function getUserFeedback()
    {
        $this->ask('Was that information helpful to you? ', [
            [
                'pattern' => 'yes|yep',
                'callback' => function () {
                    $this->say('Great!!! :)');
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
