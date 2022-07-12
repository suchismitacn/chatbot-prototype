<?php

namespace App\Middlewares;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Str;

class SendingMiddleware implements Sending
{
    /**
     * Handle an outgoing message payload before/after it
     * hits the message service.
     *
     * @param mixed $payload
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function sending($payload, $next, BotMan $bot)
    {
        $bot->typesAndWaits(2);
        $this->processPayload($payload, $bot);
        return $next($payload);
    }

    protected function processPayload($payload, $bot)
    {
        /* in documentation payload value is further used to call getText() and getActions(), 
        but in practice I had to use 'message' index otherwise code was throwing errors */
        $message = $payload['message'];
        $chatId = $bot->userStorage()->get('chatId') ?? $bot->userStorage()->save([
            'chatId' => Str::uuid()
        ]);
        $data = [
            'chat_session' => $chatId,
            'sender_id' => null,
            'recipient_id' =>  request()->session()->getId(),
            'origin' => 'Chatbot',
            'content' => $message->getText()
        ];
        if ($message instanceof Question) {
            $data['optional_data'] = $message->getActions();
        }
        \Log::debug("Message: " . print_r($message, true));
        (new ConversationRepository)->storeConversation($data);
    }
}
