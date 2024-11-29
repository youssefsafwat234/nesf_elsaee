<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NeighbourhoodController extends Controller
{
    function neighbourhoodByCity(Request $request)
    {
        if (!$request->has('city_id')) {
            return response()->json(['message' => 'من فضلك ارسل المدينة'], 400);
        }
        $cityId = $request->get('city_id');
        $neighbourhoods = \App\Models\Neighbourhood::where('city_id', $cityId)->get();

        return response()->json([
            'data' => $neighbourhoods,
        ]);

    }
}
