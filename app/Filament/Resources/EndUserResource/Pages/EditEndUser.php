<?php

namespace App\Filament\Resources\EndUserResource\Pages;

use App\Filament\Resources\EndUserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEndUser extends EditRecord
{
    protected static string $resource = EndUserResource::class;

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
            ->title('مستخدمي التطبيق')
            ->body(' تم تعديل المستخدم بنجاح');
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
