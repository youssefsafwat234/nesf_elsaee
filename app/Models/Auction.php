<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $with = ['images', 'city', 'user'];

    protected $fillable = [
        'user_id',
        'video_path',
        'city_id',
        'type',
        'area',
        'starting_date',
        'ending_date',
        'auction_link',
        'notes',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->hasMany(AuctionImage::class, 'auction_id');
    }


    function getVideoPathAttribute($value){
        return asset('attachments/' . $value);
    }


}
