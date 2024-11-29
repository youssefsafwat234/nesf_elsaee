<?php

namespace App\Filament\Resources;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Admin;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'الشركات العقارية';
    protected static ?string $navigationGroup = 'المستخدمين';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $pluralModelLabel = 'الشركات العقارية';
    protected static ?string $modelLabel = 'شركة عقارية';
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([
                    Forms\Components\Section::make([
                        Forms\Components\Fieldset::make('البيانات الشخصية')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('الاسم')
                                    ->required(),

                                Forms\Components\TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required(),

                                TextInput::make('password')->label('كلمة المرور')
                                    ->password()
                                    ->confirmed(fn(Page $livewire) => $livewire instanceof CreateRecord)
                                    ->maxLength(255)
                                    ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                                    ->dehydrateStateUsing(fn($state) => \Hash::make($state))
                                    ->dehydrated(fn($state) => filled($state))
                                    ->hiddenOn('edit')
                                ,
                                TextInput::make('password_confirmation')->label('تاكيد كلمة المرور')->password()->hiddenOn('edit')
                                ,
                                Forms\Components\TextInput::make('phone')
                                    ->label('رقم الهاتف')
                                    ->minLength(10)
                                    ->maxLength(20)
                                    ->numeric()
                                    ->unique(ignoreRecord: true)
                                    ->required(),
                                Forms\Components\TextInput::make('whatsapp_phone')
                                    ->label('رقم الواتساب')
                                    ->minLength(10)
                                    ->maxLength(20)
                                    ->numeric()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ])
                        ,
                        Forms\Components\Fieldset::make('البيانات الخاصة بالشركات العقارية')->schema([
                            Forms\Components\Select::make('subscriptionType')
                                ->label('نوع الاشتراك')
                                ->options([
                                    'شهري' => 'شهري',
                                    'سنوي' => 'سنوي',
                                ])
                                ->nullable(),
                            Forms\Components\Select::make('city')->label('المدينة')
                                ->options(function () {
                                    return \App\Models\City::all()->pluck('name', 'name');
                                })
                            ,
                            Forms\Components\TextArea::make('location')
                                ->label('الموقع')
                            ,
                            Forms\Components\TextInput::make('website_url')
                                ->label('رابط الموقع الإلكتروني')
                            ,
                            Forms\Components\TextInput::make('manager_name')
                                ->label('اسم المدير')
                                ->nullable(),
                            Forms\Components\TextArea::make('social_media_url')
                                ->label('رابط وسائل التواصل الاجتماعي')
                                ->nullable(),

                            Forms\Components\TextInput::make('twitter_url')
                                ->label('رابط تويتر')
                                ->nullable(),

                            Forms\Components\TextInput::make('instagram_url')
                                ->label('رابط إنستغرام')
                                ->nullable(),

                            Forms\Components\TextInput::make('snapchat_url')
                                ->label('رابط سناب شات')
                                ->nullable(),

                            Forms\Components\TextArea::make('branches')
                                ->label('الفروع')
                                ->nullable(),
                        ]),
                    ]),
                    Forms\Components\Section::make([
                            Forms\Components\Fieldset::make('الملفات الخاصة بالشركات')->schema(
                                [
                                    FileUpload::make('logo')->label('صورة المستخدم')
                                        ->directory('logos')
                                        ->image()
                                        ->dehydrated(fn($state) => filled($state))
                                        ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                                        ->maxSize(2048)
                                        ->columnSpanFull()
                                    , // 1MB

                                    Forms\Components\FileUpload::make('val_certification')
                                        ->directory('val_certifications')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->maxSize(10240)
                                        ->dehydrated(fn($state) => filled($state))
                                        ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                                        ->columnSpanFull()
                                        ->label('شهادة التقييم')
                                    ,

                                    Forms\Components\FileUpload::make('other_certifications')
                                        ->directory('other_certifications')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->columnSpanFull()
                                        ->dehydrated(fn($state) => filled($state))
                                        ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                                        ->maxSize(10240)
                                        ->label('الشهادات الأخرى')
                                    ,
                                    Forms\Components\FileUpload::make('commercial_register')
                                        ->directory('commercial_registers')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->dehydrated(fn($state) => filled($state))
                                        ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                                        ->columnSpanFull()
                                        ->maxSize(10240)
                                        ->label('السجل التجاري')
                                    ,
                                ])
                        ]
                    )
                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('accountType', AccountTypeEnum::COMPANY_ACCOUNT->value);
            })
            ->columns([
                // Personal Data Columns
                TextColumn::make('name')
                    ->label('الاسم')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('phone')
                    ->sortable()
                    ->label('رقم الهاتف')
                    ->searchable(),

                TextColumn::make('whatsapp_phone')
                    ->sortable()
                    ->label('رقم الواتساب')
                    ->searchable(),

                TextColumn::make('subscriptionType')
                    ->label('نوع الاشتراك')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'شهري' => 'warning',
                        'سنوي' => 'success',
                    }),

                TextColumn::make('city')
                    ->label('المدينة')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('manager_name')
                    ->label('اسم المدير')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('location')
                    ->sortable()
                    ->label('الموقع')
                    ->limit(50),

                TextColumn::make('website_url')
                    ->label('رابط الموقع الإلكتروني')
                    ->badge()
                    ->sortable()
                    ->color('success')
                    ->url(fn($record) => $record->website_url, true)
                    ->openUrlInNewTab(),

                ImageColumn::make('logo')
                    ->sortable()
                    ->label('صورة المستخدم')
                    ->circular(),

                // File Uploads
                BooleanColumn::make('val_certification')
                    ->label('شهادة التقييم')
                    ->sortable()
                    ->color('info')
                    ->url(fn($record) => $record->val_certification, true)
                    ->trueIcon('heroicon-o-folder-open')
                ,

                BooleanColumn::make('commercial_register')
                    ->color('info')
                    ->label('السجل التجاري')
                    ->url(fn($record) => $record->commercial_register, true)
                    ->trueIcon('heroicon-o-folder-open')
                ,

                Tables\Columns\IconColumn::make('other_certifications')
                    ->color('info')
                    ->label('الشهادات الأخرى')
                    ->url(fn($record) => $record->other_certifications, true)
                    ->trueIcon('heroicon-o-folder-open'),

                TextColumn::make('created_at')
                    ->sortable()
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
