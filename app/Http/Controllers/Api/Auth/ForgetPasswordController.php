<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgetPasswordRequest;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = \App\Models\User::where('email', $request->email)->firstOrFail();

        $user->notify(new \App\Notifications\ForgetPasswordNotification());

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الكود الخاص بتعيين كلمة المرور الجديدة  الى البريد الإلكتروني الخاص بيك'
        ]);
    }
}
