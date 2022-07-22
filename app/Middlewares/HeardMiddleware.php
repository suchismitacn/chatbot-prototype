<?php

namespace App\Middlewares;

use App\Repositories\ChatSessionRepository;
use App\Repositories\ConversationRepository;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Carbon\Carbon;
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
        $this->handleChatbotChatSession($message, $bot);
        return $next($message);
    }

    protected function handleChatbotChatSession(IncomingMessage $message, BotMan $bot)
    {
        $chatId = request()->session()->get('chatId');

        // create first chat session for user if already does not exist.
        $chatSession = (new ChatSessionRepository)->handleChatSession([
            'guest_id' => request()->session()->getId(),
            'admin_id' => null,
            'guest_name' => $bot->userStorage()->get('userName') ?? 'Guest User',
            'is_resolved' => $message->getText() === 'bye' ? 1 : 0,
            'resolved_at' =>  $message->getText() === 'bye' ? Carbon::now() : null
        ]);
        \Log::debug('Chat Session: ' . print_r($chatSession, true));

        // get chat session id back from backend and pass that to the conversation payload
        if ($chatSession) {
            // if ($chatId != $chatSession->id) {
                request()->session()->put(['chatId' => $chatSession->id, 'chatUuid' => $chatSession->chat_uuid]);

                $chatId = request()->session()->get('chatId');
            // }
            \Log::debug('Chat: ' . print_r(request()->session()->all(), true));

            $conversation = [
                'chat_session_id' => $chatId,
                'sender_id' => request()->session()->getId(),
                'recipient_id' =>  null,
                'origin' => 'User',
                'content' => $message->getText()
            ];

            (new ConversationRepository)->storeConversation($conversation);
        }
    }
}
