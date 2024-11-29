<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;


    protected $fillable = ['path', 'advertisement_id'];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }


    public function getPathAttribute($value)
    {
        return asset('attachments/' . $value);
    }
}
