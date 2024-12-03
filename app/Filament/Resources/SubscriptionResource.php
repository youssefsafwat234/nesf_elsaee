<?php

namespace App\Filament\Resources;

use App\Enums\AccountTypeEnum;
use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\Subscription;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationGroup = 'الأشتراكات';
    protected static ?string $pluralModelLabel = 'الأشتراكات';
    protected static ?string $modelLabel = 'أشتراك';
    protected static ?string $navigationLabel = 'الاشتراكات';
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-pound';
    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('نوع المستخدمين الخاصين بهذا الاشتراك')
                    ->options(
                        [
                            AccountTypeEnum::COMPANY_ACCOUNT->value => AccountTypeEnum::COMPANY_ACCOUNT->value,
                            AccountTypeEnum::OFFICE_ACCOUNT->value => AccountTypeEnum::OFFICE_ACCOUNT->value,
                            AccountTypeEnum::FREELANCER_ACCOUNT->value => AccountTypeEnum::FREELANCER_ACCOUNT->value,
                            AccountTypeEnum::Service_Provider_Account->value => AccountTypeEnum::Service_Provider_Account->value,
                        ]
                    )
                    ->required()
                ,
                Forms\Components\TextInput::make('name')
                    ->label('اسم الاشتراك')
                    ->string(),
                Forms\Components\Toggle::make('status')
                    ->label('حالة الاشتراك')
                    ->required()
                    ->accepted()
                    ->inline(false)
                    ->onColor('success')
                    ->offColor('danger')
                    ->helperText('حدد ما إذا كان الاشتراك نشطًا أو غير نشط'),

                Forms\Components\TextInput::make('price')
                    ->label('السعر')
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('subscription_type')
                    ->label('نوع الاشتراك')
                    ->options([
                        'سنة' => 'سنة',
                        'شهر' => 'شهر',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('advertisement_number')
                    ->label('عدد الإعلانات')
                    ->numeric(),

                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->maxLength(1000)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')  // "Type"
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الاشتراك')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('status')
                    ->label('الحالة')
                    ->onColor('success')
                    ->offColor('danger')
                    ->getStateUsing(function ($record) {
                        return $record->status;
                    })
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('تغيير حالة الاشتراك')
                            ->body($state ? 'تم تفعيل الاشتراك بنجاح' : 'تم تعطيل الاشتراك بنجاح')
                            ->icon('heroicon-o-check-circle')
                            ->title('حالة الاشتراك')
                            ->status($state ? 'success' : 'danger')
                            ->send();
                    })
                ,

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')  // "Price"
                    ->sortable(),

                Tables\Columns\TextColumn::make('subscription_type')
                    ->label('نوع الاشتراك')  // "Subscription Type"
                    ->sortable(),

                Tables\Columns\TextColumn::make('advertisement_number')
                    ->label('عدد الإعلانات')  // "Number of Advertisements"
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')  // "Created At"
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make(),])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make(),]);
    }

    public
    static function getRelations(): array
    {
        return [
            //
        ];
    }

    public
    static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
//            'create' => Pages\CreateSubscription::route('/create'),
//            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public
    static function afterCreate(Subscription $subscription): void
    {
        Notification::make()
            ->title('تم إنشاء الاشتراك بنجاح')
            ->body("تم إنشاء الاشتراك الجديد: {$subscription->type}")
            ->success()
            ->send();
    }


    public
    static function afterUpdate(Subscription $subscription): void
    {
        Notification::make()
            ->title('تم تحديث الاشتراك بنجاح')
            ->body("تم تحديث الاشتراك بنجاح: {$subscription->type}")
            ->success()
            ->send();
    }
}