<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AdminResource;
use App\Filament\Resources\AuctionResource;
use App\Filament\Resources\CityResource;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use Awcodes\Overlook\OverlookPlugin;
use Awcodes\Overlook\Widgets\OverlookWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class IdPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('id')
            ->path('/')
            ->authGuard('admin')
            ->passwordReset()
            ->login()
            ->plugins([
                \Hasnayeen\Themes\ThemesPlugin::make(),
                QuickCreatePlugin::make()->excludes(
                    [
                        CityResource::class,
                        AuctionResource::class
                    ]
                ),
                FilamentApexChartsPlugin::make()
//                OverlookPlugin::make()
//                   ->tooltips(true)
//                    ->alphabetical()
//                    ->sort(2)
//                    ->columns([
//                        'default' => 1,
//                        'sm' => 2,
//                        'md' => 3,
//                        'lg' => 4,
//                        'xl' => 5,
//                        '2xl' => null,
//                    ]),
            ])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,

            ])
            ->middleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->spa();
    }
}
