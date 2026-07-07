<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Communications extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    public static function getNavigationLabel(): string
    {
        return 'الاتصالات والإشعارات / Communications';
    }
}
