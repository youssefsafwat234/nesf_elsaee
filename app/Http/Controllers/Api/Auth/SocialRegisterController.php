<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\SocialRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialRegisterController extends Controller
{
    public function socialRegister(SocialRegisterRequest $request)
    {
        $accessToken = $request->get('token');
        $provider = $request->get('provider');

        $providerUser = Socialite::driver($provider)->userFromToken($accessToken);

        if (User::where('provider_id', $providerUser->id)->exists() || User::where('email', $providerUser->email)->exists()) {
            return response()->json(['message' => 'هذا الحساب موجود من قبل'], 409);
        }

        // create user
        $user = User::create(
            [
                'provider_name' => $provider,
                'provider_id' => $providerUser->id,
                'email' => $providerUser->email,
                'name' => $providerUser->name,
                'logo' => $providerUser->avatar,
                'phone' => $request->phone,
                'accountType' => $request->accountType
            ]
        );
        // for non end users
        if ($user->accountType != AccountTypeEnum::ENDUSER_ACCOUNT->value) {
            $user->update($request->only('subscriptionType', 'whatsapp_phone', 'city', 'location', 'website_url'));

            // for  logo, val_certification and  other_certifications files
            $files = ['logo', 'val_certification', 'other_certifications'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $logoName = $request->file($file)->getClientOriginalName();
                    $logoPath = $request->file($file)->storeAs(\Str::plural($file) . '/' . $user->id, $logoName, 'attachments');
                    $user->update([$file => env('APP_URL') . '/' . 'public/attachments/' . $logoPath]);
                }
            }
        }
        // For company and office users only
        if ($user->accountType == AccountTypeEnum::OFFICE_ACCOUNT->value || $user->accountType == AccountTypeEnum::COMPANY_ACCOUNT->value) {
            $user->update($request->only('manager_name', 'social_media_url', 'twitter_url', 'instagram_url', 'snapchat_url', 'branches',));
            if ($request->hasFile('commercial_register')) {
                $logoName = $request->file('commercial_register')->getClientOriginalName();
                $logoPath = $request->file('commercial_register')->storeAs(\Str::plural('commercial_register') . '/' . $user->id, $logoName, 'attachments');
                $user->update(['commercial_register' => env('APP_URL') . '/' . 'public/attachments/' . $logoPath]);
            }
        }
        // For freelancers only
        if ($user->accountType === AccountTypeEnum::FREELANCER_ACCOUNT->value) {
            $user->update($request->only('neighborhood'));
        }
        $user->save();


        // create token
        $userToken = $user->createToken('authToken')->plainTextToken;


        $userAttributes = collect($user->getAttributes())->filter(function ($attribute) {
            return !is_null($attribute);

        })->except('created_at', 'updated_at', 'password', 'provider_name', 'provider_id')->toArray();


        // send response
        return response()->json([
            'message' => 'تم إنشاء المستخدم بنجاح',
            'data' => $userAttributes,
            'token' => $userToken,
        ], 200);
    }
}
