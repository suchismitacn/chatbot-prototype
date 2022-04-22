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

    public function storeConversation(string $sessionId, string $sender, string $recipient, string $content, array $optionalData = null)
    {
        \Log::debug("*************************************");
        \Log::debug("Session Id: ".$sessionId);
        \Log::debug("Sender: ".$sender);
        \Log::debug("Recipient: ".$recipient);
        \Log::debug("Content: ".$content);
        if($optionalData) \Log::debug("Options: ".print_r($optionalData, true));
        \Log::debug("*************************************");
        return $this->conversation->create([
            'session_id' => $sessionId,
            'sender' => $sender,
            'recipient' => $recipient,
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
        $conversation = $this->getConversation(['session_id' => $sessionId]);

        Mail::to($email)->send(new SendConversation($conversation));

        return true;
    }
}
