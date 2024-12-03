<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat_' . $this->chatMessage->chat_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->chatMessage->message,
            'sender' => $this->chatMessage->sender,
            'media' => $this->chatMessage->media,
            'chat_id' => $this->chatMessage->chat_id,
            'last_message_type' => $this->chatMessage->type,
            'last_message_sender' => $this->chatMessage->sender_id,
            'unseen_messages_count' => ChatParticipant::where('chat_id', $this->chatMessage->chat_id)->where('user_id', auth()->id())->first()->unseen_messages_count
        ];
    }
}
