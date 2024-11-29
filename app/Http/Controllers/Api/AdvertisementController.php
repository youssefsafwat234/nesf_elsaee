<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdvertisementRequest;
use App\Http\Requests\getAdvertisementsByCityRequest;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\City;
use App\Models\Neighbourhood;
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
            'real_estate_property' => $data['real_estate_property'],
            'description' => $data['description'],
            'bedrooms_number' => $data['bedrooms_number'] ?? null,
            'bathrooms_number' => $data['bathrooms_number'] ?? null,
            'reception_and_sitting_rooms_number' => $data['reception_and_sitting_rooms_number'] ?? null,
            'street_width' => $data['street_width'] ?? null,
            'surrounding_streets_number' => $data['surrounding_streets_number'] ?? null,
            'real_estate_front' => $data['real_estate_front'] ?? null,
        ]);
        if ($data['real_estate_age'] == 'مستعمل') {
            $advertisement->update([
                'real_estate_age_number' => $data['real_estate_age_number'],
            ]);
        }

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {

                $imageName = 'image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs(\Str::plural('advertisement') . '/' . $advertisement->id, $imageName, 'attachments');
                $advertisement->images()->create(['path' => $imagePath]);
            }
        }

        // الرجوع برسالة نجاح
        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الإعلان بنجاح',
            'advertisement' => $advertisement
        ]);


    }


    function filter(Request $request)
    {
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

        $query = Advertisement::query();

        foreach ($requestKeys as $key) {
            $query->when($request->filled($key) && !is_bool($request->$key), function ($query) use ($key, $request) {
                return match ($key) {
                    'type' => $query->where('type', (string)$request->input($key)),
                    'category_id' => $query->where('category_id', (integer)$request->input($key)),
                    'city_id' => $query->where('city_id', (integer)$request->input($key)),
                    'neighbourhood_id' => $query->where('neighbourhood_id', (integer)$request->input($key)),
                    default => $query
                };
            });
        }

        // Apply ordering based on boolean values for price and area
        if (
            $request->filled('lowest_price_order') ||
            $request->filled('highest_price_order') ||
            $request->filled('lowest_area_order') ||
            $request->filled('highest_area_order')
        ) {
            // Convert values to booleans
            $lowestPriceOrder = filter_var((bool)$request->input('lowest_price_order'), FILTER_VALIDATE_BOOLEAN);
            $highestPriceOrder = filter_var((bool)$request->input('highest_price_order'), FILTER_VALIDATE_BOOLEAN);
            $lowestAreaOrder = filter_var((bool)$request->input('lowest_area_order'), FILTER_VALIDATE_BOOLEAN);
            $highestAreaOrder = filter_var((bool)$request->input('highest_area_order'), FILTER_VALIDATE_BOOLEAN);

            // Apply ordering based on the combinations
            if ($highestPriceOrder) {
                $query->orderBy('price', 'desc'); // Order by highest price
            } elseif ($lowestPriceOrder) {
                $query->orderBy('price', 'asc'); // Order by lowest price
            }

            if ($lowestAreaOrder) {
                $query->orderBy('from_area', 'asc'); // Order by smallest area
            } elseif ($highestAreaOrder) {
                $query->orderBy('to_area', 'desc'); // Order by largest area
            }
        }

        $advertisements = $query->get();

        return response()->json(
            ['data' => $advertisements]
        );
    }


    function getAdvertisementsByCity(getAdvertisementsByCityRequest $request)
    {
        try {
            $advertisements = Advertisement::where('city_id', $request->city_id)->with(['images', 'user', 'city', 'category', 'neighbourhood'])->get();

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

    function getFilterData()
    {
        $categories = Category::all();
        $cities = City::all();
        $neighbourhoods = Neighbourhood::all();
        return response()->json([
            'categories' => $categories,
            'cities' => $cities,
            'neighbourhoods' => $neighbourhoods,
        ]);

    }

}
