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

    public function storeConversation(string $userId, string $sender, string $content, array $optionalData = null)
    {
        \Log::debug("User Id: ".$userId);
        \Log::debug("Sender: ".$sender);
        \Log::debug("Content: ".$content);
        if($optionalData) \Log::debug("Options: ".print_r($optionalData, true));
        \Log::debug("****************************************");

        return $this->conversation->create([
            'user_id' => $userId,
            'sender' => $sender,
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

    public function sendConversation(string $userId, string $email)
    {
        $conversation = $this->getConversation(['user_id' => $userId]);

        Mail::to($email)->send(new SendConversation($conversation));

        return true;
    }
}
