<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\SocialLoginRequest;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController
{
    public function socialLogin(SocialLoginRequest $request)
    {
        try {
            $accessToken = $request->get('token');
            $provider = $request->get('provider');
            $providerUser = Socialite::driver($provider)->userFromToken($accessToken);

            // Check if the user exists by email
            $user = User::where('provider_id', $providerUser->id)->first();

            if (!$user) {
                return response()->json(['message' => 'البريد الإلكتروني او كلمة المرور غير صحيحة'], 401);
            }
            if ($user->tokens()->count() != 0) {
                return response()->json(['message' => 'تم تسجيل الدخول من هذا الحساب مستخدم من قبل جهاز اخر'], 401);
            }

            // Log in the existing user
            \Auth::login($user);


            // Delete old tokens and create a new token for the authenticated user
            $user->tokens()->delete();
            $token = $user->createToken('authToken')->plainTextToken;

            // Send response with user details and token
            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح',
                'user' => $user->only('name', 'email', 'password', 'phone', 'accountType', 'subscriptionType'),
                'token' => $token,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
