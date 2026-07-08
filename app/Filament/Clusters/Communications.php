<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Communications extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?int $navigationSort = 5;
    
    public static function getNavigationLabel(): string
    {
        return 'الإشعارات والاتصالات / Notifications';
    }
}
