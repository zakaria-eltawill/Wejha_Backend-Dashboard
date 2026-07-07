<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Clusters\EventManagement;
use Filament\Pages\Page;

class QRScanner extends Page
{
    protected static ?string $cluster = EventManagement::class;
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string $view = 'filament.pages.qr-scanner';

    public static function getNavigationLabel(): string
    {
        return 'ماسح التذاكر السريع / QR Scanner';
    }

    public function getHeading(): string
    {
        return 'ماسح التذاكر السريع / Real-time QR Ticket Scanner';
    }
}
