<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function initAdminChat()
    {
        $sender = Auth::user();
        $users = $this->conversationRepository->userConversationSummary(['userId' => $sender->id]);

        return view('chat-section', ['sender' => $sender, 'users' => $users]);
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
