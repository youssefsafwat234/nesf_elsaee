<?php

namespace App\Filament\Resources;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\AuctionResource\Pages;
use App\Models\Auction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuctionResource extends Resource
{
    protected static ?string $model = Auction::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'المزادات';
    protected static ?string $modelLabel = 'مزاد';
    protected static ?string $navigationGroup = 'الإعلانات والمزادات';
    protected static ?string $navigationLabel = 'المزادات';
    protected static ?int $navigationSort = 5;

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
                        Forms\Components\Section::make('بيانات المزاد')
                            ->schema([
                                Forms\Components\Wizard::make([
                                    Forms\Components\Wizard\Step::make('البيانات الأساسية')
                                        ->schema([
                                            Forms\Components\Select::make('user_id')->label('المستخدم')
                                                ->relationship('user', 'name', function ($query) {
                                                    $query->whereIn('accountType', [AccountTypeEnum::OFFICE_ACCOUNT->value, AccountTypeEnum::COMPANY_ACCOUNT->value]);
                                                })
                                                ->native(false)
                                                ->required(),
                                            Forms\Components\Select::make('city_id')->label('المدينة')
                                                ->relationship('city', 'name')
                                                ->native(false)
                                                ->required(),
                                            Forms\Components\Select::make('type')->label('نوع المزاد')
                                                ->options(
                                                    [
                                                        'شراء' => 'شراء',
                                                        'بيع' => 'بيع',
                                                    ]
                                                )
                                                ->required(),
                                            Forms\Components\TextInput::make('area')->label('المساحة')
                                                ->numeric()
                                                ->required(),
                                            Forms\Components\DatePicker::make('starting_date')->label('تاريخ البدء')
                                                ->date()
                                                ->beforeOrEqual(fn(Forms\Get $get) => $get('ending_date'))
                                                ->native(false)
                                                ->format('Y-m-d H:i:s')
                                                ->required(),
                                            Forms\Components\DatePicker::make('ending_date')->label('تاريخ الانتهاء')
                                                ->date()
                                                ->afterOrEqual(fn(Forms\Get $get) => $get('starting_date'))
                                                ->live()
                                                ->native(false)
                                                ->format('Y-m-d H:i:s')
                                                ->required(),
                                            Forms\Components\TextInput::make('auction_link')->label('رابط المزاد')
                                                ->url()
                                                ->required(),
                                            Forms\Components\Textarea::make('notes')->label('ملاحظات')
                                                ->nullable(),
                                        ]),
                                    Forms\Components\Wizard\Step::make('الفيديوهات و الصور')
                                        ->schema([
                                            Forms\Components\FileUpload::make('video_path')
                                                ->label('فيديو المزاد')
                                                ->directory('auctions/videos')
                                                ->dehydrated(fn($state) => filled($state))
                                                ->disk('attachments')
                                                ->acceptedFileTypes(['video/mp4', 'video/x-ms-wmv', 'video/x-flv', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska', 'video/x-ms-wmv', 'video/mpeg', 'video/3gpp', 'video/x-ms-asf', 'video/x-m4v', 'video/x-ms-wm', 'video/x-ms-wvx', 'video/avi', 'video/webm', 'video/ogg'])
                                                ->required(fn(Page $livewire) => $livewire instanceof CreateRecord),

                                            Forms\Components\FileUpload::make('images')
                                                ->label('صور المزاد')
                                                ->multiple()
                                                ->dehydrated(fn($state) => filled($state))
                                                ->directory('auctions/images')
                                                ->disk('attachments')
                                                ->maxFiles(10)
                                                ->image()
                                                ->required(fn(Page $livewire) => $livewire instanceof CreateRecord),
                                        ]),
                                ]),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('المستخدم')->sortable()->searchable(),
                TextColumn::make('city.name')->label('المدينة')->sortable()->searchable(),
                TextColumn::make('type')->label('نوع المزاد')->sortable(),
                TextColumn::make('area')->label('المساحة')->sortable(),
                TextColumn::make('starting_date')->label('تاريخ البدء')->date()->sortable(),
                TextColumn::make('ending_date')->label('تاريخ الانتهاء')->date()->sortable(),
                TextColumn::make('auction_link')->label('رابط المزاد')->url(fn($record) => $record->auction_link, true),
                ImageColumn::make('images.image_path')->label('الصور')->circular()->stacked(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city_id')->label('المدينة')->relationship('city', 'name'),
                Tables\Filters\SelectFilter::make('type')->label('نوع المزاد')->options(
                    [
                        'شراء' => 'شراء',
                        'بيع' => 'بيع',
                    ]
                ),
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

    public static function getRelations(): array
    {
        return [
            // Define relations if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuctions::route('/'),
            'create' => Pages\CreateAuction::route('/create'),
            'edit' => Pages\EditAuction::route('/{record}/edit'),
        ];
    }
}
