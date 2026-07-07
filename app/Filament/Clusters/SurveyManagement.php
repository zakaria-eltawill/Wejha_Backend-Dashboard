<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class SurveyManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    
    public static function getNavigationLabel(): string
    {
        return 'إدارة الاستبيانات / Surveys';
    }
}
