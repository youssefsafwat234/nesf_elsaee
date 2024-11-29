<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'advertisement_count',
        'status',
        'price',
    ];

    function user()
    {
        return $this->belongsTo(User::class);

    }

    function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
