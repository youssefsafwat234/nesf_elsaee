<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdvertisementRequest;
use App\Http\Requests\getAdvertisementsByCityRequest;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use function PHPUnit\Framework\matches;

class AdvertisementController extends Controller
{
    public function store(AdvertisementRequest $request)
    {
        $data = $request->validated();

        $advertisement = Advertisement::create([
            'user_id' => auth()->user()->id,
            'type' => $data['type'],
            'category_id' => $data['category_id'],
            'city_id' => $data['city_id'],
            'neighbourhood_id' => $data['neighbourhood_id'],
            'price' => $data['price'],
            'location' => $data['location'],
            'from_area' => $data['from_area'],
            'to_area' => $data['to_area'],
            'real_estate_age' => $data['real_estate_age'],
            'real_estate_age_number' => $data['real_estate_age_number'] ?? null,
            'real_estate_property' => $data['real_estate_property'],
            'description' => $data['description'],
            'bedrooms_number' => $data['bedrooms_number'] ?? null,
            'bathrooms_number' => $data['bathrooms_number'] ?? null,
            'reception_and_sitting_rooms_number' => $data['reception_and_sitting_rooms_number'] ?? null,
            'street_width' => $data['street_width'] ?? null,
            'surrounding_streets_number' => $data['surrounding_streets_number'] ?? null,
            'real_estate_front' => $data['real_estate_front'] ?? null,
        ]);

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {

                $imageName = 'image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs(\Str::plural('advertisement') . '/' . $advertisement->id, $imageName, 'attachments');
                $advertisement->images()->create(['path' => env('APP_URL') . '/' . 'public/attachments/' . $imagePath]);
            }
        }

        // الرجوع برسالة نجاح
        return response()->json([
            'message' => 'تم إضافة الإعلان بنجاح',
            'advertisement' => $advertisement
        ]);


    }


    function filter(Request $request)
    {
        // Define the keys to filter
        $requestKeys = [
            'type',
            'category_id',
            'city_id',
            'neighbourhood_id',
            'lowest_price_order',
            'highest_price_order',
            'lowest_area_order',
            'highest_area_order',
        ];

        // Start the query
        $query = Advertisement::query();

        // Loop through the keys and apply filters based on request input
        foreach ($requestKeys as $key) {
            $query->when($request->filled($key) && !is_bool($request->$key), function ($query) use ($key, $request) {
                return match ($key) {
                    'type' => $query->where('type', $request->input($key)),
                    'category_id' => $query->where('category_id', $request->input($key)),
                    'city_id' => $query->where('city_id', $request->input($key)),
                    'neighbourhood_id' => $query->where('neighbourhood_id', $request->input($key)),
                    default => $query
                };
            });
        }


        // Apply ordering based on boolean values
        if ($request->filled('lowest_price_order') && $request->lowest_price_order) {
            $query->orderBy('price', 'asc');
        }

        if ($request->filled('highest_price_order') && $request->highest_price_order) {
            $query->orderBy('price', 'desc');
        }

        if ($request->filled('lowest_area_order') && $request->lowest_area_order) {
            $query->orderBy('from_area', 'asc');
        }

        if ($request->filled('highest_area_order') && $request->highest_area_order) {
            $query->orderBy('to_area', 'desc');
        }

        $advertisements = $query->get();

        return response()->json(
            ['data' => $advertisements->with('images')]
        );
    }


    function getAdvertisementsByCity(getAdvertisementsByCityRequest $request)
    {
        try {
            $advertisements = Advertisement::where('city_id', $request->city_id)->with('images')->get();

            return response()->json(
                [
                    'success' => true,
                    'data' => $advertisements
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['success' => false, $e->getMessage()], 500);
        }


    }

}
