<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function store(Request $request)
    {

        $request->validate(
            [
                'advertisement_id' => 'required|exists:advertisements,id'
            ],
            [
                'advertisement_id.required' => 'من فضلك ارسل الإعلان',
                'advertisement_id.exists' => 'الإعلان غير موجود'
            ]
        );

        $favourites = Favourite::where('user_id', auth()->user()->id)->with('advertisement')->get();

        if ($favourites->contains('advertisement_id', $request->advertisement_id)) {
            return response()->json([
                'success' => false,
                'message' => 'الاعلان موجود فى المفضلة'
            ]);
        }

        Favourite::create(
            [
                'user_id' => auth()->user()->id,
                'advertisement_id' => $request->advertisement_id
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تمت الإضافة للمفضلة بنجاح'
        ]);

    }


    public function destroy($id, Request $request)
    {
        $favourite = Favourite::where('user_id', auth()->user()->id)->where('advertisement_id', $id)->first();
        if ($favourite) {
            $favourite->deleteOrFail();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الإعلان من المفضلة بنجاح'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'الإعلان غير موجود فى المفضلة'
            ]);
        }
    }

    public function index()
    {
        $favourites = Favourite::where('user_id', auth()->user()->id)->with('advertisement')->get();
        $advertisement_ids = Favourite::where('user_id', auth()->user()->id)->pluck('advertisement_id');
        return response()->json(
            [
                'success' => true,
                'data' => $favourites,
                'advertisement_ids' => $advertisement_ids
            ]
        );
    }
}
