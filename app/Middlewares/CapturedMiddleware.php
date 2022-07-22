<?php

namespace App\Middlewares;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Str;

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
        $chatId = request()->session()->get('chatId');
        $data = [
            'chat_session_id' => $chatId,
            'sender_id' => request()->session()->getId(),
            'recipient_id' => null,
            'origin' => 'User',
            'content' => $message->getText()
        ];
        // \Log::debug('captured data: ' . print_r($data, true));

        (new ConversationRepository)->storeConversation($data);

        return $next($message);
    }
}
