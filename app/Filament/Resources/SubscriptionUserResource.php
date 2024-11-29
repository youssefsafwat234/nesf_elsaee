<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionUserResource\Pages;
use App\Filament\Resources\SubscriptionUserResource\RelationManagers;
use App\Models\Subscription;
use App\Models\SubscriptionUser;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionUserResource extends Resource
{
    protected static ?string $model = SubscriptionUser::class;
    protected static ?string $navigationGroup = 'الأشتراكات';
    protected static ?string $pluralModelLabel = 'أشتراكات المستخدمين';
    protected static ?string $modelLabel = 'أشتراك للمستخدم';
    protected static ?string $navigationLabel = 'أشتراكات المستخدمين';
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-pound';
    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('المستخدم')
                    ->relationship('user', 'name')
                    ->required()
                    ->reactive()
                    ->live()
                    ->preload(),
                Select::make('subscription_id')
                    ->label('الاشتراك')
                    ->required()
                    ->live()
                    ->reactive()
                    ->options(function (callable $get) {
                        $userId = $get('user_id');
                        if (!$userId) {
                            return [];
                        }
                        $user = \App\Models\User::find($userId);
                        if (!$user) {
                            return [];
                        }
                        return \App\Models\Subscription::where('type', $user->accountType)
                            ->where('status', true)
                            ->pluck('id', 'id');
                    }),
                TextInput::make('advertisement_count')
                    ->label('عدد الإعلانات')
                    ->integer()
                    ->default(0)
                    ->live() // تحديث فوري عند الكتابة.
                    ->reactive() // تحديث بناءً على تغيير subscription_id.
                    ->maxValue(
                        function (callable $get) {
                            $subscriptionId = $get('subscription_id');
                            if (!$subscriptionId) {
                                return null; // لا يوجد حد أقصى إذا لم يتم اختيار الاشتراك.
                            }

                            // الحصول على عدد الإعلانات مباشرة.
                            $subscription = Subscription::find($subscriptionId);
                            return $subscription ? $subscription->advertisement_number : null;
                        }
                    )
                    ->required(),
                Toggle::make('status')
                    ->label('الحالة')
                    ->default(false)
                    ->inline(false)
                    ->onColor('success')
                    ->offColor('danger')
                    ->required(),
                TextInput::make('price')
                    ->label('السعر')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('المستخدم'),
                TextColumn::make('subscription.id')->label('الاشتراك'),
                TextColumn::make('advertisement_count')->label('عدد الإعلانات'),
                ToggleColumn::make('status')
                    ->label('الحالة')
                    ->onColor('success')
                    ->offColor('danger')
                    ->disabled(
                        function ($record) {
                            return $record->status == false && ($record->advertisement_count >= Subscription::find($record->subscription_id)->advertisement_number);
                        })
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->title('تغيير حالة اشتراك المستخدم')
                            ->body($state ? 'تم تفعيل الاشتراك بنجاح' : 'تم تعطيل الاشتراك بنجاح')
                            ->icon('heroicon-o-check-circle')
                            ->status($state ? 'success' : 'danger')
                            ->send();
                    }),
                TextColumn::make('price')->label('السعر'),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => Pages\ListSubscriptionUsers::route('/'),
            'create' => Pages\CreateSubscriptionUser::route('/create'),
            'edit' => Pages\EditSubscriptionUser::route('/{record}/edit'),
        ];
    }

}
