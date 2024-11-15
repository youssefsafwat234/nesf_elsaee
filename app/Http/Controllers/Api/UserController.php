<?php

namespace App\Http\Controllers\Api;

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NearestCompaniesAndOfficesRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
        $offices = \App\Models\User::where('accountType', AccountTypeEnum::OFFICE_ACCOUNT->value)->where('city', $request->city)->get();
        return response()->json([
            'data' => $offices,
        ]);
    }

    function getNearestCompanyAccounts(NearestCompaniesAndOfficesRequest $request)
    {
        $companies = \App\Models\User::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)->where('city', $request->city)->get();
        return response()->json([
            'data' => $companies,
        ]);

    }


}
