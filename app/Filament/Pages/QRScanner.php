<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Page;

class QRScanner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string $view = 'filament.pages.qr-scanner';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return __('filament-pages.qr_scanner.navigation_label');
    }

    public function getHeading(): string
    {
        return __('filament-pages.qr_scanner.heading');
    }
}
