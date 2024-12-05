<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    public $with = [
        'images',
        'city',
        'user',
        'category',
        'neighbourhood'
    ];
    public $fillable = [
        'user_id',
        'type',
        'category_id',
        'city_id',
        'neighbourhood_id',
        'price',
        'location',
        'from_area',
        'to_area',
        'real_estate_age',
        'real_estate_age_number',
        'real_estate_property',
        'description',
        'bedrooms_number',
        'bathrooms_number',
        'reception_and_sitting_rooms_number',
        'street_width',
        'surrounding_streets_number',
        'real_estate_front',
        'status',
        'pending_by'
    ];

    public function images()
    {
        return $this->hasMany(Image::class, 'advertisement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function neighbourhood()
    {
        return $this->belongsTo(Neighbourhood::class);
    }

    public function getFromAreaAttribute($value)
    {
        return (float)$value;
    }

    public function getToAreaAttribute($value)
    {
        return (float)$value;
    }

    function order()
    {
        return $this->hasOne(Order::class);
    }


}
