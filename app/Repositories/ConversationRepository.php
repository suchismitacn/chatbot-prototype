<?php

namespace App\Repositories;

use App\Models\Conversation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConversation;

class ConversationRepository
{
    protected $conversation;

    public function __construct()
    {
        $this->conversation = new Conversation();
    }

    public function storeConversation($sender_id, $recipient_id, string $origin, string $content, array $optionalData = null)
    {
        return $this->conversation->create([
            'sender_id' => $sender_id,
            'recipient_id' => $recipient_id,
            'origin' => $origin,
            'content' => $content,
            'optional_data' => $optionalData
        ]);
    }

    public function getConversation($condition)
    {
        return $this->conversation
            ->where($condition)
            ->get();
    }

    public function sendConversation(string $sessionId, string $email)
    {
        // $conversation = $this->getConversation(['session_id' => $sessionId]);
        $conversation = $this->conversation
            ->where('sender_id', $sessionId)
            ->orWhere('recipient_id', $sessionId)
            ->get();

        Mail::to($email)->send(new SendConversation($conversation));

        return true;
    }
}
