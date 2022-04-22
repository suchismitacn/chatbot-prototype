<?php

namespace App\Middlewares;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
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
        $message = $payload['message'];
        \Log::debug("*************************************");
        \Log::debug("Sending message: ".print_r($message, true));
        \Log::debug("Message Text: ".print_r($message->getText(), true));
        if($message instanceof OutgoingMessage) {
            $this->storeConversation('bot', 'user', $message->getText());
        } else {
            \Log::debug("Message Options: ".print_r($message->getActions(), true));
            $this->storeConversation('bot', 'user', $message->getText(), $message->getActions());
        }
        
        return $next($payload);
    }

    protected function storeConversation(string $sender, string $recipient, string $text, array $options = null)
    {
        return (new ConversationRepository)->storeConversation(request()->session()->getId(), $sender, $recipient, $text, $options);
    }
}
