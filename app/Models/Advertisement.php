<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

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
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

}
