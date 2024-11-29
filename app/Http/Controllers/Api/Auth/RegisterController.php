<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            // create user
            $user = User::create(
                $request->only('name', 'email', 'password', 'phone', 'accountType')
            );


            // for non end users
            if ($user->accountType != AccountTypeEnum::ENDUSER_ACCOUNT->value && $user->accountType != AccountTypeEnum::Service_Provider_Account->value) {

                $user->update($request->only('subscriptionType', 'whatsapp_phone', 'city', 'location', 'website_url',));

                // for  logo, val_certification and  other_certifications files
                $files = ['logo', 'val_certification', 'other_certifications'];
                foreach ($files as $file) {
                    if ($request->hasFile($file)) {
                        $logoName = $request->file($file)->getClientOriginalName();
                        $logoPath = $request->file($file)->storeAs(\Str::plural($file) . '/' . $user->id, $logoName, 'attachments');
                        $user->update([$file => $logoPath]);
                    }
                }
            }


            // For company and office users only
            if ($user->accountType == AccountTypeEnum::OFFICE_ACCOUNT->value || $user->accountType == AccountTypeEnum::COMPANY_ACCOUNT->value) {
                $user->update($request->only('manager_name', 'social_media_url', 'twitter_url', 'instagram_url', 'snapchat_url', 'branches',));
                if ($request->hasFile('commercial_register')) {
                    $logoName = $request->file('commercial_register')->getClientOriginalName();
                    $logoPath = $request->file('commercial_register')->storeAs(\Str::plural('commercial_register') . '/' . $user->id, $logoName, 'attachments');
                    $user->update(['commercial_register' => $logoPath]);
                }
            }


            // For freelancers only
            if ($user->accountType === AccountTypeEnum::FREELANCER_ACCOUNT->value || $user->accountType === AccountTypeEnum::Service_Provider_Account->value) {
                $user->update($request->only('neighborhood'));
            }

            // for service accounts only
            if ($user->accountType === AccountTypeEnum::Service_Provider_Account->value) {
                $user->update($request->only('service_type'));
                if ($request->hasFile('logo')) {
                    $logoName = $request->file('logo')->getClientOriginalName();
                    $logoPath = $request->file('logo')->storeAs(\Str::plural('logo') . '/' . $user->id, $logoName, 'attachments');
                    $user->update(['logo' => $logoPath]);
                }
            }


            $user->save();
            // create token
            $userToken = $user->createToken('authToken')->plainTextToken;


            $userAttributes = collect($user->getAttributes())->filter(function ($attribute) {
                return !is_null($attribute);

            })->except('created_at', 'updated_at', 'password')->toArray();


            // send response
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المستخدم بنجاح',
                'data' => $userAttributes,
                'token' => $userToken,
            ], 200);

        } catch (\Exception $e) {

            return response()->json(['error' => "THere is an error please try again"], 500);
        }
    }
}
