<?php

namespace App\Filament\Resources\EndUserResource\Pages;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\EndUserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEndUser extends CreateRecord
{
    protected static string $resource = EndUserResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $custom_array = ['accountType' => AccountTypeEnum::ENDUSER_ACCOUNT->value];
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
            ->title('مستخدمي التطبيق')
            ->body('تم إنشاء المستخدم بنجاح');
    }
}
