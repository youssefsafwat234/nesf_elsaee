<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Attempt to find user by email or phone
        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->email)
                ->orWhere('phone', $request->email);
        })->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'البريد الإلكتروني او كلمة المرور غير صحيحة'], 401);
        }

//        if ($user->tokens()->count() != 0) {
//            return response()->json(['success' => false, 'message' => 'تم تسجيل الدخول من هذا الحساب مستخدم من قبل جهاز اخر'], 401);
//        }

        // Log in the authenticated user
        \Auth::login($user);

        // Delete old tokens and create a new token for the authenticated user
        $user->tokens()->delete();
        $token = $user->createToken('authToken')->plainTextToken;

        // Send response with user details and token
        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => $user->only('name', 'email', 'password', 'phone', 'accountType', 'subscriptionType'),
            'token' => $token,
        ], 200);
    }


    public function logout(Request $request)
    {
        auth()->user()->update(['fcm_token' => null]);
        $request->user()->currentAccessToken()->delete();
        return response()->success('Logged out successfully');
    }
}
