<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    function index()
    {
        $contactUs = ContactUs::select(['phone', 'notes'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $contactUs
        ]);
    }
}
