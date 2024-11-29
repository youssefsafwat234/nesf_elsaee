<?php

namespace App\Filament\Resources\FreelancerResource\Pages;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\FreelancerResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateFreelancer extends CreateRecord
{
    protected static string $resource = FreelancerResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $custom_array = ['accountType' => AccountTypeEnum::FREELANCER_ACCOUNT->value];
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
            ->title('المسوقين عقارية')
            ->body('تم انشاء المسوق بنجاح');
    }
}
