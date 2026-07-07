<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class SystemReports extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    
    public static function getNavigationLabel(): string
    {
        return 'التقارير والسجلات / System logs';
    }
}
