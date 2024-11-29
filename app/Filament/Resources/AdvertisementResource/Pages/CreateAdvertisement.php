<?php

namespace App\Filament\Resources\AdvertisementResource\Pages;

use App\Filament\Resources\AdvertisementResource;
use App\Models\Advertisement;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAdvertisement extends CreateRecord
{
    protected static string $resource = AdvertisementResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        $record = Advertisement::create($data);

        $images = $data['images'];
        foreach ($images as $image) {
            $record->images()->create([
                'path' => $image,
            ]);
        }
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('الاعلانات')
            ->body('تم انشاء الاعلان بنجاح');
    }
}
