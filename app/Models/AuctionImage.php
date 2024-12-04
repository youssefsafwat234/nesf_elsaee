<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionImage extends Model
{
    use HasFactory;

    protected $fillable = ['auction_id', 'image_path'];


    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    function getImagePathAttribute($value)
    {
        return asset('attachments/' . $value);
    }
}
