<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageCities extends ManageRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('المدن')
            ->body('تم انشاء المدينة بنجاح');
    }
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->warning()
            ->title('المدن')
            ->body(' تم تعديل المدينة بنجاح');
    }
}
