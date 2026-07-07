<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;

class AttendanceRateChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'معدل الحضور / Attendance Rate';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        return app(AnalyticsService::class)->getAttendanceChartData();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
