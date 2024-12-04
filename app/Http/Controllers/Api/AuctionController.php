<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auction\StoreAuctionRequest;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{


    public function index()
    {
        $auctions = Auction::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $auctions,
        ]);
    }

    function store(StoreAuctionRequest $request)
    {
        $data = $request->validated();

        // store the auction video
        $video_path = $request->file('video_path')->store('auctions/videos', 'attachments');
        $data['video_path'] = $video_path;

        // create the auction
        $auction = Auction::create([
            'user_id' => auth()->id(),
            'video_path' => $data['video_path'],
            'city_id' => $data['city_id'],
            'type' => $data['type'],
            'area' => $data['area'],
            'starting_date' => $data['starting_date'],
            'ending_date' => $data['ending_date'],
            'auction_link' => $data['auction_link'],
            'notes' => $data['notes'],
        ]);

        // store the auction images
        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->storeAs('auctions/images', 'attachments');
                $auction->images()->create(['image_path' => $imagePath]);
            }
        }
        $auction = $auction->load(['images', 'city', 'user']);
        return response()->json([
            'success' => true,
            'data' => $auction,
        ], 201);


    }

}
