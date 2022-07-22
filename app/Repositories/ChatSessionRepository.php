<?php

namespace App\Repositories;

use App\Models\ChatSession;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConversation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ChatSessionRepository
{
    protected $chatSession;

    public function __construct()
    {
        $this->chatSession = new ChatSession();
    }

    public function handleChatSession($attributes)
    {
        return $this->chatSession->updateOrCreate(
            ['guest_id' => $attributes['guest_id'], 'admin_id' => $attributes['admin_id']],
            Arr::except($attributes, ['guest_id', 'admin_id'])
        );
    }
}
