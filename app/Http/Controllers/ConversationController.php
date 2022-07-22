<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\ChatSession;
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

    public function initChat($from, $chatSession)
    {
        $agent = User::where(['is_online' => 1])->first();
        $user = ChatSession::select('guest_id as id, guest_name as name')->firstWhere(['chat_uuid' => $chatSession]);
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
                'chatSession' => $chatSession
            ];
        } else {
            $data = ['status' => 'No user(s) available! Please try after some time.'];
        }
        return view('chat-section', $data);
    }

    public function initAdminChat()
    {
        $data['sender'] = User::where(['is_online' => 1])->first();
        $data['recipient'] = null;
        $data['chatSession'] = null;

        return view('chat-section', $data);
    }

    public function fetchUsers(Request $request)
    {
        $users = $this->conversationRepository->userConversationSummary($request->all());
        return $users;
    }

    public function sendMessage(Request $request)
    {
        Log::debug('Attributes: ' . print_r($request->all(), true));
        try {
            $message = $this->conversationRepository->storeConversation($request->all());
            $message = $message->load('sender');
            NewMessage::dispatchIf($message->recipient_id, $message);
            return $message;
        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function fetchMessages(Request $request)
    {
        // $messages = $this->conversationRepository->fetchMessages($request->sender_id, $request->recipient_id); 
        $messages = $this->conversationRepository->getConversation($request->where);
        return $messages;
    }
}
