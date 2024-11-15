<?php

namespace App\Http\Controllers\Api;

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FreeLancerController extends Controller
{
    function index()
    {
        \Gate::authorize('view-freelancers');

        $freelancers = \App\Models\User::where('accountType', AccountTypeEnum::FREELANCER_ACCOUNT->value)->get();

        return response()->json([
            'data' => $freelancers,
        ]);
    }
}
