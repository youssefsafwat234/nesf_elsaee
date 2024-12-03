<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMedia extends Model
{
    use HasFactory;


    protected $fillable = [
        'chat_message_id',
        'url',
    ];

    function getUrlAttribute($value)
    {
        return asset('attachments/' . $value);
    }
}
