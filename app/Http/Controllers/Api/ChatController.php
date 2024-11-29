<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chats = Chat::hasParticipants(auth()->id())
            ->whereHas('messages')
            ->with('lastMessage', 'lastMessage.sender', 'participants.user')
            ->latest('updated_at')->get();
        if ($chats->count() > 0) {
            return response()->json([
                'success' => true,
                'data' => $chats], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'No chats found'], 404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $otherUser = User::find($request->user_id);
        // user cannot chat with himself
        if ($otherUser->id == auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot chat with yourself'], 400);
        }
        // user cannot chat with a user who is already in a chat
        $previousChat = $this->getPreviousChat($otherUser->id);
        if ($previousChat === null) {
            $chat = Chat::create([
                'created_by' => auth()->id(),
                'label' => $otherUser->name,
            ]);
            $ids = [auth()->id(), $otherUser->id];
            $chat->participants()->createMany(
                [
                    [
                        'user_id' => auth()->id()

                    ],
                    [
                        'user_id' => $otherUser->id
                    ]
                ]
            );
            $chat->refresh()->load('lastMessage.sender', 'participants.user', 'messages', 'lastMessage', 'messages.sender');

            return response()->json([
                'success' => true,
                'message' => 'Chat created successfully',
                'data' => $chat
            ], 201);
        }
        return response()->json([
            'success' => true,
            'message' => 'Chat already exists',
            'data' => $previousChat->load('lastMessage.sender', 'participants.user', 'messages', 'lastMessage', 'messages.sender')
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat, Request $request)
    {
        if (ChatParticipant::where('user_id', auth()->id())->exists()) {
            $chat->load('participants.user', 'messages', 'messages.sender', 'lastMessage', 'lastMessage.sender');
            return response()->json([
                'success' => true,
                'data' => $chat], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'User is not a participant in this chat'], 403);
        }
    }

    public function edit(Chat $chat)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }


    function getPreviousChat($otherUserId)
    {
        $otherUser = User::find($otherUserId);
        $chat = Chat::whereHas('participants', function ($q) use ($otherUserId) {
            $q->where('user_id', $otherUserId);
        })->whereHas('participants', function ($q) {
            $q->where('user_id', auth()->id());
        })->first();
        return $chat;

    }
}
