<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public static function index()
    {
        $cities = \App\Models\City::select(['id', 'name', 'logo'])->get();

        return response()->json([
            'data' => $cities,
        ]);
    }
}
