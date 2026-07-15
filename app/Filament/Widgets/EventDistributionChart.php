<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Repositories\ReportRepository;
use Filament\Widgets\ChartWidget;

class EventDistributionChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected static ?string $maxHeight = '250px';

    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return __('filament-widgets.event_distribution.heading');
    }

    protected function getData(): array
    {
        $dist = app(ReportRepository::class)->getEventDistribution();

        // Translate keys
        $translatedData = [];
        $labels = [];
        $colors = ['#001F8F', '#00389E', '#FF4900'];

        foreach ($dist as $type => $count) {
            $labels[] = match ($type) {
                'seminar' => __('filament-widgets.event_distribution.seminar'),
                'workshop' => __('filament-widgets.event_distribution.workshop'),
                'exhibition' => __('filament-widgets.event_distribution.exhibition'),
                default => $type
            };
            $translatedData[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('filament-widgets.event_distribution.dataset_label'),
                    'data' => $translatedData,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
