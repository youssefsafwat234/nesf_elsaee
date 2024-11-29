<?php

namespace App\Traits;

use App\Enums\AccountTypeEnum;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait NearestAccounts
{
    function getNearestAccounts($city, $object)
    {
        $latitude = $city->latitude;
        $longitude = $city->longitude;

        $nearestCities = DB::table('cities')->select('name', DB::raw("
    (6371 * acos(
        cos(radians($latitude)) *
        cos(radians(latitude)) *
        cos(radians(longitude) - radians($longitude)) +
        sin(radians($latitude)) *
        sin(radians(latitude)))) AS distance "))
            ->orderBy('distance', 'asc')
            ->pluck('name')->toArray();
        $accounts = [];
        foreach ($nearestCities as $nearestCity) {
            $cityAccountIds = $object->where('city', $nearestCity)->pluck('id');
            foreach ($cityAccountIds as $accountId) {
                if (!in_array($accountId, $accounts)) {
                    $accounts[] = $accountId;
                }
            }
        }
        return $accounts;
    }
}

