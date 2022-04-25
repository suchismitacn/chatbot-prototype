<?php

namespace App\Middlewares;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class CapturedMiddleware implements Captured
{
    /**
     * Handle a captured message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function captured(IncomingMessage $message, $next, BotMan $bot)
    {
        // this code is not working in this particular middleware, even though user storage has value
        $user = $bot->userStorage()->get('name') ?? 'user';
        $this->storeConversation($user, 'bot', $message->getText());
        return $next($message);
    }

    protected function storeConversation(string $sender, string $recipient, string $text, array $options = null)
    {
        return (new ConversationRepository)->storeConversation(request()->session()->getId(), $sender, $recipient, $text, $options);
    }
}
