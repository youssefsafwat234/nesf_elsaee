<?php

namespace App\Filament\Resources\ServiceProviderAccountResource\Pages;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\ServiceProviderAccountResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateServiceProviderAccount extends CreateRecord
{
    protected static string $resource = ServiceProviderAccountResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $custom_array = ['accountType' => AccountTypeEnum::Service_Provider_Account->value];
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
            ->title('مقدمى الخدمات ')
            ->body('تم انشاء مقدم خدمة بنجاح');
    }
}
