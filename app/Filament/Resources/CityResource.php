<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use Awcodes\FilamentQuickCreate\Components\QuickCreateMenu;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $pluralModelLabel = 'المدن';
    protected static ?string $modelLabel = 'مدينة';
    protected static ?string $navigationGroup = 'المدن والأحياء';
    protected static ?string $navigationLabel = 'المدن';
    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema(
                    [
                        Forms\Components\TextInput::make('name')->label('اسم المدينة')
                            ->required()
                            ->columns(4)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('latitude')->label('خط العرض')
                            ->numeric()
                            ->required()
                            ->columns(4),
                        Forms\Components\TextInput::make('longitude')->label("خط الطول")
                            ->numeric()
                            ->required()
                            ->columns(4),
                    ]
                )->columns(3),
                FileUpload::make('logo')->label('صورة المدينة')
                    ->directory('cities')
                    ->required(fn(Page $livewire) => $livewire instanceof CreateRecord || $livewire instanceof QuickCreateMenu)
                    ->dehydrated(fn($state) => filled($state))
                    ->maxSize(2048)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم المدينة')
                    ->searchable(),
                ImageColumn::make('logo')
                    ->sortable()
                    ->label('صورة المدينة')
                    ->circular(),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCities::route('/'),
        ];
    }


}
