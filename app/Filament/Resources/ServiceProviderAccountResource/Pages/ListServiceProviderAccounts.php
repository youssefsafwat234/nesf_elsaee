<?php

namespace App\Filament\Resources\ServiceProviderAccountResource\Pages;

use App\Filament\Resources\ServiceProviderAccountResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListServiceProviderAccounts extends ListRecords
{
    protected static string $resource = ServiceProviderAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'الكل' => Tab::make(),
            'المقاولون' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('service_type', 'مقاول')),
            'المحامين' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('service_type', 'محامي')),
            'اصحاب العقارات' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('service_type', 'صاحب عقار')),
            'المكاتب الهندسية' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('service_type', 'مكتب هندسي')),
        ];
    }
}
