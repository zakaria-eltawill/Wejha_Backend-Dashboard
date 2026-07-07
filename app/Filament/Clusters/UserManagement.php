<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class UserManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    public static function getNavigationLabel(): string
    {
        return 'إدارة المستخدمين / Users';
    }
}
