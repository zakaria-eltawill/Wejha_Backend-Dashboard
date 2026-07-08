<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class SystemReports extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?int $navigationSort = 4;
    
    public static function getNavigationLabel(): string
    {
        return 'التقارير / Reports';
    }
}
