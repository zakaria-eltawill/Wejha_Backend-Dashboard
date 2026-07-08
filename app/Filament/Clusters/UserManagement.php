<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class UserManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 6;
    
    public static function getNavigationLabel(): string
    {
        return 'إدارة المستخدمين / Users';
    }
}
