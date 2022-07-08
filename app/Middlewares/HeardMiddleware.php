<?php

namespace App\Middlewares;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class HeardMiddleware implements Heard
{
    /**
     * Handle a message that was successfully heard, but not processed yet.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        $this->storeConversation('chatbot', $message->getText());
        return $next($message);
    }

    protected function storeConversation(string $origin, string $text, array $options = null)
    {
        return (new ConversationRepository)->storeConversation(request()->session()->getId(), null, $origin, $text, $options);
    }
}
