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

class HomeController extends Controller
{
    use  NearestAccounts;


    function index(NearestCompaniesAndOfficesRequest $request)
    {
        // cities
        $cities = $this->getAllCities();

        // offices
        $offices = $this->getAllOfficeAccounts();

        // companies
        $companies = $this->getAllCompanyAccounts();

        //nearest-freelancers
        $nearestFreelancers = $this->getNearestFreelancerAccounts($request);


        return response()->json([
            'success' => true,
            'data' => [
                'cities' => $cities,
                'offices' => $offices,
                'companies' => $companies,
                'nearestFreelancers' => $nearestFreelancers,
            ]
        ]);
    }

    public function getAllCities()
    {
        $cities = \App\Models\City::select(['id', 'name', 'logo'])->get();
        return $cities;
    }

    function getAllOfficeAccounts()
    {
        $offices = \App\Models\User::where('accountType', AccountTypeEnum::OFFICE_ACCOUNT->value)->get();
        return $offices;
    }

    function getAllCompanyAccounts()
    {
        $companies = \App\Models\User::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)->get();
        return $companies;
    }

    function getNearestFreelancerAccounts($request)
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
        return $nearestFreelancers;
    }


}
