<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;

class TopSchoolsChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'المدارس الأكثر مشاركة / Top Schools';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        return app(AnalyticsService::class)->getTopSchoolsChartData();
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
