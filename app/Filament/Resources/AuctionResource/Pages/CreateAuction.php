<?php

namespace App\Filament\Resources\AuctionResource\Pages;

use App\Filament\Resources\AuctionResource;
use App\Models\Advertisement;
use App\Models\Auction;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAuction extends CreateRecord
{
    protected static string $resource = AuctionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = Auction::create($data);

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
