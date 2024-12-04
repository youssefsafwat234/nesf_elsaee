<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvertisementResource\Pages;
use App\Filament\Resources\AdvertisementResource\RelationManagers;
use App\Models\Advertisement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'الإعلانات';
    protected static ?string $modelLabel = 'إعلان';
    protected static ?string $navigationGroup = 'الإعلانات والمزادات';
    protected static ?string $navigationLabel = 'الإعلانات';
    protected static ?int $navigationSort = 6;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(12)
                    ->schema([
                        Forms\Components\Section::make('بيانات الاعلان')
                            ->schema([
                                Forms\Components\Wizard::make([
                                    Forms\Components\Wizard\Step::make('البيانات الاساسية 1')
                                        ->schema([
                                            Forms\Components\Select::make('user_id')->label('المستخدم')
                                                ->native(false)
                                                ->relationship('user', 'name')
                                                ->required(),
                                            Forms\Components\Select::make('type')->label('نوع الاعلان')
                                                ->native(false)
                                                ->options(['إيجار' => 'إيجار' , 'شراء' => 'شراء'])
                                                ->required(),
                                            Forms\Components\Select::make('category_id')->label('نوع العقار')
                                                ->relationship('category', 'name')
                                                ->native(false)
                                                ->required(),
                                            Forms\Components\Select::make('city_id')->label('المدينة')
                                                ->native(false)
                                                ->relationship('city', 'name')
                                                ->required()
                                                ->reactive(),
                                            Forms\Components\Select::make('neighbourhood_id')->label('الحي')
                                                ->label('Neighbourhood')
                                                ->native(false)
                                                ->options(function (callable $get) {
                                                    $cityId = $get('city_id');
                                                    if (!$cityId) {
                                                        return [];
                                                    }
                                                    return \App\Models\Neighbourhood::where('city_id', $cityId)
                                                        ->pluck('name', 'id')
                                                        ->toArray();
                                                })
                                                ->required()
                                                ->reactive()
                                        ]),
                                    Forms\Components\Wizard\Step::make('البيانات الاساسية 2')
                                        ->schema([
                                            Forms\Components\TextInput::make('price')->label('السعر')
                                                ->numeric()
                                                ->required(),
                                            Forms\Components\Textarea::make('location')->label('الموقع الإلكتروني')
                                                ->activeUrl()
                                                ->required(),
                                            Forms\Components\TextInput::make('from_area')->label('مساحة العقار من')
                                                ->numeric()
                                                ->required(),
                                            Forms\Components\TextInput::make('to_area')->label('مساحة العقار الى')
                                                ->numeric()
                                                ->required(),
                                        ]),
                                    Forms\Components\Wizard\Step::make('البيانات الاساسية 3')
                                        ->schema([
                                            Forms\Components\Select::make('real_estate_age')->label('عمر العقار')
                                                ->options(['جديد' => 'جديد', 'مستعمل' => 'مستعمل'])
                                                ->reactive()
                                                ->live()
                                                ->native(false)
                                                ->required(),

                                            Forms\Components\TextInput::make('real_estate_age_number')->label('عدد السنوات')
                                                ->numeric()
                                                ->required(function (Forms\Get $get) {
                                                    return $get('real_estate_age') == 'مستعمل';
                                                })->postfix('سنة')
                                                ->visible(function (Forms\Get $get) {
                                                    return $get('real_estate_age') == 'مستعمل';
                                                })
                                                ->live()
                                                ->reactive()
                                            ,
                                            Forms\Components\Select::make('real_estate_property')->label('خصائص العقار')
                                                ->native(false)
                                                ->options(['دوبلكس' => 'دوبلكس', 'مودرن' => 'مودرن'])
                                                ->required(),
                                            Forms\Components\Textarea::make('description')
                                                ->required(),
                                        ]),
                                    Forms\Components\Wizard\Step::make('البيانات الإضافية للاعلان')
                                        ->schema([
                                            Forms\Components\TextInput::make('bedrooms_number')->label('عدد غرف النوم')
                                                ->numeric()
                                                ->nullable(),
                                            Forms\Components\TextInput::make('bathrooms_number')->label('عدد الحمامات')
                                                ->numeric()
                                                ->nullable(),
                                            Forms\Components\TextInput::make('reception_and_sitting_rooms_number')->label('عدد غرف الاستقبال')
                                                ->numeric()
                                                ->nullable(),
                                            Forms\Components\TextInput::make('street_width')->label('عرض الشارع')
                                                ->numeric()
                                                ->nullable(),
                                            Forms\Components\TextInput::make('surrounding_streets_number')->label('عدد الشوارع المحيطة')
                                                ->numeric()
                                                ->nullable(),
                                            Forms\Components\Select::make('real_estate_front')->label('واجهة العقار')
                                                ->native(false)
                                                ->options([
                                                    'شمال' => 'شمال', 'جنوب' => 'جنوب', 'شرق' => 'شرق', 'غرب' => 'غرب',
                                                    'شمال شرق' => 'شمال شرق', 'شمال غرب' => 'شمال غرب',
                                                    'جنوب شرق' => 'جنوب شرق', 'جنوب غرب' => 'جنوب غرب',
                                                ])
                                                ->nullable(),
                                        ]),
                                ]),
                            ])
                            ->columnSpan(9),

                        Forms\Components\Section::make('Images')
                            ->schema([

                                Forms\Components\FileUpload::make('images')
                                    ->label('صور الإعلان')
                                    ->multiple()
                                    ->directory('advertisements')
                                    ->image()
                                    ->maxFiles(5)
                                    ->maxSize(2048)
                                    ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                            ])
                            ->columnSpan(3),
                    ])]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('نوع الاعلان')
                    ->badge()
                    ->colors([
                        'warning' => 'إيجار',
                        'primary' => 'شراء',
                    ])
                    ->sortable(),


                TextColumn::make('category.name')
                    ->label('نوع العقار')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('city.name')
                    ->label('المدينة')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('neighbourhood.name')
                    ->label('الحي')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('price')
                    ->label('السعر')
                    ->money('SAR', true) // Adjust currency as needed
                    ->sortable()
                    ->searchable(),

                TextColumn::make('real_estate_age')
                    ->label('عمر العقار')
                    ->badge()
                    ->colors([
                        'primary' => 'جديد',
                        'warning' => 'مستعمل',
                    ]),

                TextColumn::make('description')
                    ->label('وصف')
                    ->limit(15)
                    ->tooltip(fn($record) => $record->description),

                TextColumn::make('created_at')
                    ->label('تاريخ الانشاء')
                    ->dateTime()

                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\ImageColumn::make('images.path')->label('صور الإعلان')->circular()->stacked(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city_id')
                    ->label('المدينة')
                    ->relationship('city', 'name'),

                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع الاعلان')
                    ->options([
                        'إيجار' => 'إيجار',
                        'شراء' => 'شراء',
                    ]),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('نوع العقار')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('real_estate_age')
                    ->label('عمر العقار')
                    ->trueLabel('جديد')
                    ->falseLabel('مستعمل'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertisements::route('/'),
            'create' => Pages\CreateAdvertisement::route('/create'),
            'edit' => Pages\EditAdvertisement::route('/{record}/edit'),
        ];
    }
}
