<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class AttendanceRateChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '260px';

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

    protected function getOptions(): array | RawJs
    {
        // maintainAspectRatio must be false: see EventDistributionChart for why
        // (Chart.js's default 1:1 aspect ratio fights the widget's fixed-height
        // container across Livewire re-renders and produces a runaway canvas).
        return [
            'maintainAspectRatio' => false,
            'cutout' => '62%',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 16,
                        'font' => ['size' => 12],
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => RawJs::make(<<<'JS'
                            (context) => {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((sum, v) => sum + v, 0);
                                const pct = total > 0 ? Math.round((value / total) * 100) : 0;
                                return ` ${context.label}: ${value} (${pct}%)`;
                            }
                        JS),
                    ],
                ],
            ],
        ];
    }
}
