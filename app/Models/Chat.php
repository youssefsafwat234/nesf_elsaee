<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;


    protected $fillable = [
        'created_by',
        'label',
        'is_private',
        'last_message_id'
    ];

    function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id');
    }

    function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    function lastMessage()
    {
        return $this->belongsTo(ChatMessage::class, 'last_message_id');
    }


    function scopeHasParticipants(Builder $query, int $userId)
    {
        $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

}

