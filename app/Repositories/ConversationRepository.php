<?php

namespace App\Repositories;

use App\Models\Conversation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConversation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ConversationRepository
{
    protected $conversation;

    public function __construct()
    {
        $this->conversation = new Conversation();
    }

    public function storeConversation($attributes)
    {
        return $this->conversation->create($attributes);
    }

    public function getConversation($condition)
    {
        return $this->conversation
            ->with('sender')
            ->where($condition)
            ->orderBy('id', 'asc')
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

    public function fetchMessages($firstUserId, $secondUserId)
    {
        $messages = $this->conversation
            ->with('sender')
            ->whereIn('sender_id', [$firstUserId, $secondUserId])
            ->whereIn('recipient_id', [$firstUserId, $secondUserId]);

        $messages = $messages->orderBy('id', 'asc')->paginate(10);

        return $messages;
    }

    public function markAsRead($firstUserId, $secondUserId, $messageId = null)
    {
        if ($messageId) {
            $message = $this->conversation->where('id', $messageId);
        } else {
            $message = $this->conversation->where('sender_id', $secondUserId)
                ->where('recipient_id', $firstUserId);
        }

        $read = $message->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);
        return $read;
    }


    public function userConversationSummary($where)
    {
        $chats = $this->conversation->selectRaw('LEAST(sender_id, recipient_id) AS least_sender_id,
        GREATEST(sender_id, recipient_id) AS greatest_recipient_id,
        MAX(id) AS max_id')->groupBy('least_sender_id', 'greatest_recipient_id')->pluck('max_id')->toArray();

        $conversationSummary = $this->conversation->with(['sender', 'receiver'])
            ->whereIn('id', $chats)
            ->where(function (Builder $query) use ($where) {
                $query->where('sender_id', $where['userId'])->orWhere('recipient_id', $where['userId']);
            });
        if ($where['type'] == 'unread') {
            $conversationSummary->where('recipient_id', $where['userId'])->whereNull('read_at');
        }
        $conversationSummary = $conversationSummary->latest()->get();
        return $conversationSummary;
    }
}
