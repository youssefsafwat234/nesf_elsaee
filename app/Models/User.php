<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'accountType',
        'phone',
        'subscriptionType',
        'whatsapp_phone',
        'logo',
        'city',
        'location',
        'val_certification',
        'other_certifications',
        'website_url',
        'commercial_register',
        'manager_name',
        'social_media_url',
        'twitter_url',
        'instagram_url',
        'snapchat_url',
        'branches',
        'neighborhood',
        'service_type',
        'provider_name',
        'provider_id',
        'service_type'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getLogoAttribute($value)
    {
        return asset('attachments/' . $value);
    }

    public function getCommercialRegisterAttribute($value)
    {
        return asset('attachments/' . $value);
    }

    public function getValCertificationAttribute($value)
    {
        return asset('attachments/' . $value);
    }

    public function getOtherCertificationsAttribute($value)
    {
        return asset('attachments/' . $value);
    }

    function chats() : HasMany
    {
        return $this->hasMany(Chat::class);
    }




}
