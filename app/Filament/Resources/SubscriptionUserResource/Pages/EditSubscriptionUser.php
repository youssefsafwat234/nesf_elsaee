<?php

namespace App\Filament\Resources\SubscriptionUserResource\Pages;

use App\Filament\Resources\SubscriptionUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionUser extends EditRecord
{
    protected static string $resource = SubscriptionUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
