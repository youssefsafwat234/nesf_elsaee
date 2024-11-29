<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;
    protected static ?string $navigationLabel = 'المشرفين';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $pluralModelLabel = 'المشرفين';
    protected static ?string $modelLabel = 'مشرف';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('الاسم')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')->label('البريد الالكتروني')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')->label('كلمة المرور')
                    ->password()
                    ->confirmed(fn(Page $livewire) => $livewire instanceof CreateRecord)
                    ->maxLength(255)
                    ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                    ->hiddenOn('edit')
                ,
                TextInput::make('password_confirmation')->label('تاكيد كلمة المرور')->password()->hiddenOn('edit')
                ,
                TextInput::make('phone')->label('رقم الهاتف')
                    ->numeric()
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('avatar_url')->label('صورة المستخدم')
                    ->directory('avatars')
                    ->image()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)
                    ->maxSize(2048)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('الاسم')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')->label('الايميل')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')->label('رقم الهاتف')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar_url')->label('صورة المستخدم')
                    ->getStateUsing(function ($record) {
                        return $record->avatar_url;
                    })
                    ->defaultImageUrl(asset('images/default-avatar.png')) // Optional fallback image
                    ->rounded()
                    ->width(50)
                    ->height(50),
                TextColumn::make('created_at')->label('تاريخ الانشاء')
                    ->date(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'view' => Pages\ViewAdmin::route('/{record}'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
