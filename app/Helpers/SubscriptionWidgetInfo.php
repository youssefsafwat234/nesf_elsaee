<?php

namespace App\Helpers;

use App\Enums\AccountTypeEnum;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionWidgetInfo
{

    static function getTotalSubsciptionCount()
    {
        return Subscription::count();
    }

    static function getUserSlopePercentage($userType)
    {
        $currentMonthUsers = self::getUsersOfCurrentMonth($userType);
        $previousMonthUsers = self::getUsersOfPreviousMonth($userType);
        return self::formatToPercentage($currentMonthUsers, $previousMonthUsers);
    }

    static function getUserSlopeString($userType)
    {
        $currentMonthUsers = self::getUsersOfCurrentMonth($userType);
        $previousMonthUsers = self::getUsersOfPreviousMonth($userType);
        if ($currentMonthUsers > $previousMonthUsers) {
            return 'تزايد';
        } else if ($currentMonthUsers < $previousMonthUsers) {
            return 'تناقص';
        } else {
            return 'متوسط';
        }
    }

    static function getUserSlopeColor($userType)
    {
        $currentMonthUsers = self::getUsersOfCurrentMonth($userType);
        $previousMonthUsers = self::getUsersOfPreviousMonth($userType);
        if ($currentMonthUsers > $previousMonthUsers) {
            return 'success';
        } else if ($currentMonthUsers < $previousMonthUsers) {
            return 'danger';
        } else {
            return 'primary';
        }
    }

    static function getUserSlopeIcon($userType)
    {
        $currentMonthUsers = self::getUsersOfCurrentMonth($userType);
        $previousMonthUsers = self::getUsersOfPreviousMonth($userType);
        if ($currentMonthUsers > $previousMonthUsers) {
            return 'heroicon-m-arrow-trending-up';
        } else if ($currentMonthUsers < $previousMonthUsers) {
            return 'heroicon-m-arrow-trending-down';
        } else {
            return 'heroicon-m-arrow-long-left';
        }
    }


    static function formatPrice($amount)
    {
        return $amount . 'ريال سعودى';
    }

    static function formatToPercentage($amountNumberOne, $amountNumberTwo)
    {
        if ($amountNumberOne > 0) {
            if ($amountNumberTwo > 0) {
                $formatted = number_format(($amountNumberOne / $amountNumberTwo) * 100, 2);
                return "$formatted%";
            } else {
                return "100%";
            }

        }
        return "0%";
    }

    static function getUsersOfCurrentMonth($userType)
    {
        // Current month
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $currentMonthUsers = User::where('accountType', $userType)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        return $currentMonthUsers;
    }

    static function getUsersOfPreviousMonth($userType)
    {
        // Previous month
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $previousMonthUsers = User::where('accountType', $userType)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();
        return $previousMonthUsers;
    }

    static function getUsersOfPreviousWeekDays($userType)
    {
        $userCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $nextDate = $date->copy()->endOfDay();

            $count = User::where('accountType', $userType)
                ->whereBetween('created_at', [$date, $nextDate])
                ->count();
            $userCounts[] = $count;
        }

        return $userCounts;
    }

}