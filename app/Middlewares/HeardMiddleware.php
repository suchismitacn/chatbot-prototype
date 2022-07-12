<?php

namespace App\Middlewares;

use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Str;

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
        $chatId = $bot->userStorage()->get('chatId') ?? $bot->userStorage()->save([
            'chatId' => Str::uuid()
        ]);
        $data = [
            'chat_session' => $chatId,
            'sender_id' => request()->session()->getId(),
            'recipient_id' =>  null,
            'origin' => 'Chatbot',
            'content' => $message->getText()
        ];
        (new ConversationRepository)->storeConversation($data);
        return $next($message);
    }
}
