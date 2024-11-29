<?php

namespace App\Filament\Resources;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\EndUserResource\Pages;
use App\Filament\Resources\EndUserResource\RelationManagers;
use App\Models\EndUser;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EndUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'مستخدمين التطبيق';
    protected static ?string $navigationGroup = 'المستخدمين';
    protected static ?string $recordTitleAttribute = 'email';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $pluralModelLabel = 'مستخدمين التطبيق';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'مستخدم';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('accountType', AccountTypeEnum::ENDUSER_ACCOUNT->value)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

                        ])
                    ,
                ]),
                Forms\Components\Section::make([
                        Forms\Components\Fieldset::make('الملفات')->schema(
                            [
                                FileUpload::make('logo')->label('صورة المستخدم')
                                    ->directory('logos')
                                    ->image()
                                    ->dehydrated(fn($state) => filled($state))
                                    ->maxSize(2048)
                                    ->columnSpanFull()
                                , // 1MB
                            ])
                    ]
                )

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('accountType', AccountTypeEnum::ENDUSER_ACCOUNT->value);
            })
            ->columns([
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

                ImageColumn::make('logo')
                    ->sortable()
                    ->label('صورة المستخدم')
                    ->circular(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->label('تاريخ الإنشاء')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
            ])
            ->filters([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEndUsers::route('/'),
            'create' => Pages\CreateEndUser::route('/create'),
            'edit' => Pages\EditEndUser::route('/{record}/edit'),

        ];
    }
}
