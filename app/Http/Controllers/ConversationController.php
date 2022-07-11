<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->conversationRepository = new ConversationRepository();
    }

    public function initChat()
    {
        $recipient = User::where(['is_online' => 1])->inRandomOrder()->first(); // needs changing
        $sender = Auth::check() ? Auth::user() : ['id' => session()->getId(), 'name' => 'User'];
        if ($recipient) {
            $data = [
                'recipient' => $recipient,
                'sender' => $sender
            ];
        } else {
            $data = ['status' => 'No agents available! Please try after some time.'];
        }
        return view('chat-section', $data);
    }

    public function findMessage($messageId)
    {
        return $this->conversationRepository->find($messageId);
    }


    public function fetchMessage(array $where)
    {
        $message = $this->conversationRepository->fetchOneMessage($where);
        return $message;
    }


    public function sendMessage($attributes)
    {
        try {
            return $this->conversationRepository->storeConversation($attributes);
        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function fetchMessages($firstUserId, $secondUserId)
    {
        $messages = $this->conversationRepository->fetchMessages($firstUserId, $secondUserId);
        return $messages;
    }


    public function userConversationSummary($where, $agentId = null)
    {
        $user = null;
        $conversationSummary = collect();
        $conversationSummary = $this->conversationRepository->userConversationSummary($where);
        if ($agentId) {
            $user = User::find($agentId); // change required
        } else if (!$agentId && !$conversationSummary->isEmpty()) {
            $firstUser = $conversationSummary->first();
            if ($firstUser->sender_id == auth()->user()->id) {
                $userId = $firstUser->recipient_id;
            } else {
                $userId = $firstUser->sender_id;
            }
            $user = User::find($userId); // change required
        }
        return ['initiatedWith' => $user, 'conversationSummary' => $conversationSummary];
    }

    public function markAsRead($firstUserId = null, $secondUserId = null, $messageId = null)
    {
        $read_messages = $this->conversationRepository->markAsRead($firstUserId, $secondUserId, $messageId);
        return $read_messages;
    }
}
