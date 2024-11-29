<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = CompanyResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        $custom_array = ['accountType' => AccountTypeEnum::COMPANY_ACCOUNT->value];
        $data = array_merge($custom_array, $data);
        return static::getModel()::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



}
