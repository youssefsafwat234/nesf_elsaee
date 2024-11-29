<?php

namespace App\Filament\Widgets;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\EndUserResource;
use App\Filament\Resources\FreelancerResource;
use App\Filament\Resources\OfficeResource;
use App\Filament\Resources\ServiceProviderAccountResource;
use App\Helpers\UserWidgetInfo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getCards(): array
    {

        $pluralAccountTypes = [
            EndUserResource::getPluralModelLabel(),
            CompanyResource::getPluralModelLabel(),
            OfficeResource::getPluralModelLabel(),
            FreelancerResource::getPluralModelLabel(),
            ServiceProviderAccountResource::getPluralModelLabel()
        ];
        $accountTypes = AccountTypeEnum::getAccountValues();
        $cards = [];
        $i = 0;
        foreach ($accountTypes as $accountType) {
            $cards[] = Card::make($pluralAccountTypes[$i], UserWidgetInfo::getUserCount($accountType))
                ->description(UserWidgetInfo::getUserSlopePercentage($accountType) . ' ' . UserWidgetInfo::getUserSlopeString($accountType))
                ->descriptionIcon(UserWidgetInfo::getUserSlopeIcon($accountType))
                ->chart(UserWidgetInfo::getUsersOfPreviousWeekDays($accountType))
                ->color(UserWidgetInfo::getUserSlopeColor($accountType));
            $i++;
        }
        return $cards;
    }
}
