<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
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
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->brandName('منصة وجهة 2026')
            ->brandLogo(asset('assets/logo/wejha_logo_vertical_blue_navy_gradient_transparent.png'))
            ->darkModeBrandLogo(asset('assets/logo/wejha_logo_vertical_light_gradient_transparent.png'))
            ->brandLogoHeight('8rem')
            ->favicon(asset('assets/logo/wejha_logo_vertical_blue_navy_gradient_transparent.png'))
            ->colors([
                'primary' => '#001F8F',     // Deep Blue
                'secondary' => '#00389E',   // Secondary Blue
                'accent' => '#FF4900',      // Orange
                'gray' => Color::Gray,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
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
            ]);
    }

    /**
     * Boot provider services.
     */
    public function boot(): void
    {
        \Filament\Support\Facades\FilamentAsset::register([
            \Filament\Support\Assets\Css::make('wejha-styles', asset('assets/css/wejha.css')),
        ]);

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::TOPBAR_START,
            fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('<span class="text-sm md:text-base font-bold text-primary-600 dark:text-primary-400 px-4">منصة وجهة الرقمية 2026</span>'),
        );
    }
}
