<?php

namespace App\Filament\Resources\NeighbourhoodResource\Pages;

use App\Filament\Resources\NeighbourhoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNeighbourhoods extends ManageRecords
{
    protected static string $resource = NeighbourhoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
