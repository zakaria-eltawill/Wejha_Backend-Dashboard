<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Repositories\ReportRepository;
use Filament\Widgets\ChartWidget;

class SurveyRatingsChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected static ?string $heading = 'تقييمات الاستبيانات / Survey Ratings';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $trends = app(ReportRepository::class)->getSatisfactionTrends();

        return [
            'labels' => array_keys($trends),
            'datasets' => [
                [
                    'label' => 'متوسط التقييم اليومي',
                    'data' => array_map('floatval', array_values($trends)),
                    'borderColor' => '#FF4900',
                    'backgroundColor' => 'rgba(255, 73, 0, 0.1)',
                    'fill' => true,
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
