<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class EventManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    public static function getNavigationLabel(): string
    {
        return 'إدارة الفعاليات / Events';
    }
}
