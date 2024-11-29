<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable implements HasAvatar
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'phone', 'avatar_url'];


    function getPhoneAttribute($value)
    {
        return 0 . $value;

    }

    public function getFilamentAvatarUrl(): ?string
    {
        return  asset('attachments/'. $this->avatar_url);
    }


}
