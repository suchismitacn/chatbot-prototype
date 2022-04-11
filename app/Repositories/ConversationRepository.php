<?php

namespace App\Repositories;

use App\Models\Conversation;

class ConversationRepository
{
    protected $conversation;
    public function __construct()
    {
        $this->conversation = new Conversation();
    }
    public function storeConversation(string $userId, string $sender, string $content, array $optionalData = null) {
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

    public function retrieveConversation() {

    }
}