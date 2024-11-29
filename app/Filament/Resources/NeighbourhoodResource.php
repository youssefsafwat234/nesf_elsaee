<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NeighbourhoodResource\Pages;
use App\Filament\Resources\NeighbourhoodResource\RelationManagers;
use App\Models\Neighbourhood;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NeighbourhoodResource extends Resource
{
    protected static ?string $model = Neighbourhood::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $pluralModelLabel = 'الأحياء';
    protected static ?string $modelLabel = 'حي';
    protected static ?string $navigationGroup = 'المدن والأحياء';
    protected static ?string $navigationLabel = 'الأحياء';
    protected static ?int $navigationSort = 6;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('city_id')->label('المدينة')
                    ->options(\App\Models\City::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('name')->label('اسم الحي')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('city.name')->label('المدينة')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('اسم الحي')
                    ->searchable(),
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
            'index' => Pages\ManageNeighbourhoods::route('/'),
        ];
    }
}
