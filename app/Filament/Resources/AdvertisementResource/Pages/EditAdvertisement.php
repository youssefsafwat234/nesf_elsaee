<?php

namespace App\Filament\Resources\AdvertisementResource\Pages;

use App\Filament\Resources\AdvertisementResource;
use App\Models\Image;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAdvertisement extends EditRecord
{
    protected static string $resource = AdvertisementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        Image::where('advertisement_id', $record->id)->delete();
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                Image::create([
                    'path' => $image,
                    'advertisement_id' => $record->id
                ]);
            }
        }
        return $record;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->warning()
            ->title('الإعلانات')
            ->body(' تم تعديل الإعلان بنجاح');
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
