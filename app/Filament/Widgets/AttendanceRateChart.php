<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;

class AttendanceRateChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '250px';

    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return __('filament-widgets.attendance_rate.heading');
    }

    protected function getData(): array
    {
        return app(AnalyticsService::class)->getAttendanceChartData();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
