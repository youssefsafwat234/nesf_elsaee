<?php

namespace App\Filament\Resources\OfficeResource\Pages;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\OfficeResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOffice extends CreateRecord
{
    protected static string $resource = OfficeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $custom_array = ['accountType' => AccountTypeEnum::OFFICE_ACCOUNT->value];
        $data = array_merge($custom_array, $data);
        return static::getModel()::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('المكاتب عقارية')
            ->body('تم انشاء مكتب بنجاح');
    }
}
