<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->conversationRepository = new ConversationRepository();
    }

    public function initChat($from, $chatId)
    {
        $agent = User::where(['is_online' => 1])->first();
        $user = ['id' => request()->session()->getId(), 'name' => 'User'];
        if ($from === 'agent') {
            $recipient = $user; // needs changing
            $sender = $agent; // needs changing
        } else if ($from === 'user') {
            $recipient = $agent; // needs changing
            $sender = $user; // needs changing
        } else {
            abort(404);
        }
        if ($recipient) {
            $data = [
                'recipient' => $recipient,
                'sender' => $sender,
                'chatId' => $chatId
            ];
        } else {
            $data = ['status' => 'No user(s) available! Please try after some time.'];
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


    public function sendMessage(Request $request)
    {
        Log::debug('Attributes: ' . print_r($request->all(), true));
        try {
            $message = $this->conversationRepository->storeConversation($request->all());
            return $message->load('sender');
        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function fetchMessages(Request $request)
    {
        $messages = $this->conversationRepository->fetchMessages($request->sender_id, $request->recipient_id, $request->where);
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
