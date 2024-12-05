<?php

namespace App\Filament\Resources\AuctionResource\Pages;

use App\Filament\Resources\AuctionResource;
use App\Models\AuctionImage;
use App\Models\Image;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAuction extends EditRecord
{
    protected static string $resource = AuctionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update(
            [
                'user_id' => $data['user_id'],
                'city_id' => $data['city_id'],
                'video_path' => $data['video_path'] ?? $record->video_path,
                'type' => $data['type'],
                'area' => $data['area'],
                'starting_date' => $data['starting_date'],
                'ending_date' => $data['ending_date'],
                'auction_link' => $data['auction_link'],
                'notes' => $data['notes'],
            ]
        );

        if (!empty($data['images'])) {
            AuctionImage::where('auction_id', $record->id)->delete();
            foreach ($data['images'] as $image) {
                AuctionImage::create([
                    'image_path' => $image,
                    'auction_id' => $record->id
                ]);
            }
        }
        return $record;
    }
}
