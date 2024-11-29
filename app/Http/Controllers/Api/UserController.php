<?php

namespace App\Http\Controllers\Api;

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NearestCompaniesAndOfficesRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Models\City;
use App\Models\User;
use App\Traits\NearestAccounts;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use  NearestAccounts;

    function getAllOfficeAccounts()
    {
        $offices = \App\Models\User::where('accountType', AccountTypeEnum::OFFICE_ACCOUNT->value)->get();

        return response()->json([
            'data' => $offices,
        ]);

    }

    function getAllCompanyAccounts()
    {
        $companies = \App\Models\User::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)->get();

        return response()->json([
            'data' => $companies,
        ]);

    }

    function getNearestOfficeAccounts(NearestCompaniesAndOfficesRequest $request)
    {
        if (City::where('name', '=', $request->city)->exists()) {
            $city = City::where('name', '=', $request->city)->first();
        } else {
            $city = City::first();
        }
        $object = User::where('accountType', AccountTypeEnum::OFFICE_ACCOUNT->value)->get();
        $nearestOffices = [];
        $nearestOfficeAccountIds = $this->getNearestAccounts($city, $object);
        // if the nearest offices are  zero then get the latest 15 offices
        if (count($nearestOfficeAccountIds) == 0) {
            $nearestOffices = User::where('accountType', AccountTypeEnum::OFFICE_ACCOUNT->value)->take(15)->get();
        } else {
            foreach ($nearestOfficeAccountIds as $nearestOfficeAccountId) {
                $nearestOffices[] = User::find($nearestOfficeAccountId);
            }
        }
        return response()->json([
            'success' => true,
            'data' => $nearestOffices,
        ]);
    }

    function getNearestCompanyAccounts(NearestCompaniesAndOfficesRequest $request)
    {
        if (City::where('name', '=', $request->city)->exists()) {
            $city = City::where('name', '=', $request->city)->first();
        } else {
            $city = City::first();
        }
        $object = User::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)->get();
        $nearestCompanies = [];
        $nearestCompanyAccountIds = $this->getNearestAccounts($city, $object);
        // if the nearest offices are  zero then get the latest 15 offices
        if (count($nearestCompanyAccountIds) == 0) {
            $nearestCompanies = User::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)->get();
        } else {
            foreach ($nearestCompanyAccountIds as $nearestCompanyAccountId) {
                $nearestCompanies[] = User::find($nearestCompanyAccountId);
            }
        }
        return response()->json([
            'success' => true,
            'data' => $nearestCompanies,
        ]);
    }

    function getNearestFreelancerAccounts(NearestCompaniesAndOfficesRequest $request)
    {

        if (City::where('name', '=', $request->city)->exists()) {
            $city = City::where('name', '=', $request->city)->first();
        } else {
            $city = City::first();
        }
        $object = User::where('accountType', AccountTypeEnum::FREELANCER_ACCOUNT->value)->get();
        $nearestFreelancers = [];
        $nearestFreelancerAccountIds = $this->getNearestAccounts($city, $object);
        // if the nearest offices are  zero then get the latest 15 offices
        if (count($nearestFreelancerAccountIds) == 0) {
            $nearestFreelancers = User::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)->get();
        } else {
            foreach ($nearestFreelancerAccountIds as $nearestFreelancerAccountId) {
                $nearestFreelancers[] = User::find($nearestFreelancerAccountId);
            }
        }
        return response()->json([
            'success' => true,
            'data' => $nearestFreelancers,
        ]);
    }

    function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $user->update($request->only('name', 'email', 'phone'));
        if ($request->hasFile('logo')) {
            // delete old logo
            if (\Storage::disk('attachments')->exists($user->logo)) {
                \Storage::disk('attachments')->delete($user->logo);
            }
            // insert the other logo
            $logoName = $request->file('logo')->getClientOriginalName();
            $logoPath = $request->file('logo')->storeAs(\Str::plural('logo') . '/' . $user->id, $logoName, 'attachments');
            $user->update(['logo' => $logoPath]);
        }
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المعلومات بنجاح',
            'data' => $user
        ]);
    }

    function updateUserPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&#]/', 'confirmed'],
            'old_password' => 'required'
        ], [
                'old_password.required' => 'كلمة المرور القديمة مطلوبة.',
                'password.required' => 'كلمة المرور مطلوبة.',
                'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
                'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
                'password.regex' => 'يجب أن تحتوي كلمة المرور على حرف كبير، حرف صغير، رقم، ورمز خاص واحد على الأقل.',
                'password.confirmed' => 'كلمة المرور غير متطابقة',
            ]
        );


        if (!\Hash::check($request->old_password, auth()->user()->password)) {
            return response()->json([
                "message" => "يوجد اخطاء فى البيانات برجاء مراجعة البيانات",
                'success' => false,
                'errors' => ['old_password' => 'كلمة المرور القديمة غير صحيحة.'],
            ]);
        }

        $user = auth()->user();
        $user->update(['password' => $request->password]);
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث كلمة المرور بنجاح',
        ]);
    }

    function getLowerAccounts()
    {
        $user = auth()->user();
        $lowerAccounts = User::where('accountType', AccountTypeEnum::Service_Provider_Account->value)->where('service_type', 'محامي')->get();
        return response()->json([
            'success' => true,
            'data' => $lowerAccounts,
        ]);
    }

    function getEngineerOfficeAccounts()
    {
        $user = auth()->user();
        $engineerOfficeAccounts = User::where('accountType', AccountTypeEnum::Service_Provider_Account->value)->where('service_type', 'مكتب هندسي')->get();
        return response()->json([
            'success' => true,
            'data' => $engineerOfficeAccounts,
        ]);
    }

    function getContractorAccounts()
    {
        $user = auth()->user();
        $contactorAccounts = User::where('accountType', AccountTypeEnum::Service_Provider_Account->value)->where('service_type', 'مقاول')->get();
        return response()->json([
            'success' => true,
            'data' => $contactorAccounts,
        ]);
    }

    function destroy(Request $request)
    {
        // check first the user password and then delete the user
        $user = auth()->user();
        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'كلمة المرور المستخدم مطلوبة.',
        ]);
        if (!\Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "يوجد اخطاء فى البيانات برجاء مراجعة البيانات",
                'success' => false,
                'errors' => ['password' => 'كلمة المرور غير صحيحة'],
            ]);
        }
        $user->tokens()->delete();
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الحساب بنجاح',
        ]);
    }

}
