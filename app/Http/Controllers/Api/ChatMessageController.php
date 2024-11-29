<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessageSentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChatMessage\GetChatMessagesRequest;
use App\Http\Requests\Api\ChatMessage\StoreChatMessagesRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetChatMessagesRequest $request)
    {
        $data = $request->validated();

        $chatId = $data['chat_id'];
        $current_page = $data['page'];
        $perPage = $data['per_page'] ?? 10;


        // check if the use is participant of that chat or not
        if (!ChatParticipant::where('user_id', auth()->id())->where('chat_id', $chatId)->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'You are not a participant of this chat'
            ], 403);
        }
        $chatMessages = ChatMessage::where('chat_id', $chatId)
            ->with('sender')
            ->latest('created_at')->simplePaginate($perPage, ['*'], 'page', $current_page);
        if ($chatMessages->count() > 0) {
            return response()->json([
                'success' => true,
                'data' => $chatMessages], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'No chat messages found.',
            'data' => $chatMessages
        ], 404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(StoreChatMessagesRequest $request)
    {


        $data = $request->validated();
        $chatId = $data['chat_id'];
        $data['sender_id'] = auth()->id();

        if (!ChatParticipant::where('user_id', auth()->id())->where('chat_id', $chatId)->exists()) {
            return response()->json([
                'success' => false,
                'error' => 'You are not a participant of this chat'
            ], 403);
        }
        $chatMessage = ChatMessage::create($data);

        Chat::findOrFail($chatId)->update(['last_message_id' => $chatMessage->id]);

        $this->sendMessageToOthers($chatMessage);

        $chatMessage->load('sender');
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $chatMessage
        ], 201);


    }

    function sendMessageToOthers(ChatMessage $chatMessage)
    {

        broadcast(new NewMessageSentEvent($chatMessage))->toOthers();
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $chatMessage
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public
    function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(string $id)
    {
        //
    }
}
