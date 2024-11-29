<?php

namespace App\Filament\Resources\ServiceProviderAccountResource\Pages;

use App\Filament\Resources\ServiceProviderAccountResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditServiceProviderAccount extends EditRecord
{
    protected static string $resource = ServiceProviderAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->warning()
            ->title('المكاتب عقارية')
            ->body(' تم تعديل المكتب بنجاح');
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
