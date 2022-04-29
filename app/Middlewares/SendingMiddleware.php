<?php

namespace App\Middlewares;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

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
        /* in documentation payload value is further used to call getText() and getActions(), 
        but in practice I had to use 'message' index otherwise code was throwing errors */
        $message = $payload['message'];
        $user = $bot->userStorage()->get('name') ?? 'user';

        if ($message instanceof OutgoingMessage) {
            $this->storeConversation('bot', $user, $message->getText());
        } else {
            $this->storeConversation('bot', $user, $message->getText(), $message->getActions());
        }

        return $next($payload);
    }

    protected function storeConversation(string $sender, string $recipient, string $text, array $options = null)
    {
        return (new ConversationRepository)->storeConversation(request()->session()->getId(), $sender, $recipient, $text, $options);
    }
}
