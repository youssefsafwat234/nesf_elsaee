<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ForgetPasswordNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request)
    {
        $otp = new Otp();
        if ($otp->validate($request->email, $request->code)->status == true) {
            $user = User::where('email', $request->email)->first();
            $user->update(['password' => $request->password]);
            $user->save();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'تم تغيير الباسورد بنجاح'
                ]
            );
        } else {
            // send email verification again
            $user = User::where('email', $request->email)->first();
            return response()->json(
                [
                    'success' => false,
                    'message' => 'الكود الذى ادخلته غير صحيح'
                ]
            );
        }

    }
}
