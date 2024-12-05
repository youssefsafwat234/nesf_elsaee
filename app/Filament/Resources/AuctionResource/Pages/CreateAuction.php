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
        $record = Auction::create(
            [
                'user_id' => $data['user_id'],
                'city_id' => $data['city_id'],
                'video_path' => $data['video_path'],
                'type' => $data['type'],
                'area' => $data['area'],
                'starting_date' => $data['starting_date'],
                'ending_date' => $data['ending_date'],
                'auction_link' => $data['auction_link'],
                'notes' => $data['notes'],
            ]
        );

        $images = $data['images'];
        foreach ($images as $image) {
            $record->images()->create([
                'image_path' => $image,
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
            ->title('المزادات')
            ->body('تم انشاء المزاد بنجاح');
    }
}
