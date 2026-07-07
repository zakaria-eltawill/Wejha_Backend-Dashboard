<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Repositories\ReportRepository;
use Filament\Widgets\ChartWidget;

class EventDistributionChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected static ?string $heading = 'توزيع الفعاليات / Event Distribution';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $dist = app(ReportRepository::class)->getEventDistribution();

        // Translate keys
        $translatedData = [];
        $labels = [];
        $colors = ['#001F8F', '#00389E', '#FF4900'];

        foreach ($dist as $type => $count) {
            $labels[] = match ($type) {
                'seminar' => 'ندوة / Seminar',
                'workshop' => 'ورشة عمل / Workshop',
                'exhibition' => 'معرض / Exhibition',
                default => $type
            };
            $translatedData[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'توزيع الفعاليات',
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
