<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Repositories\ReportRepository;
use Filament\Widgets\Widget;

class MonthlyActivityHeatmap extends Widget
{
    protected static ?int $sort = 7;
    protected static string $view = 'filament.widgets.monthly-activity-heatmap';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $heatmapData = app(ReportRepository::class)->getMonthlyActivityHeatmap();
        
        // Let's generate dates for the last 35 days (5 weeks grid)
        $dates = [];
        for ($i = 34; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $intensity = $heatmapData[$dateStr] ?? 0;
            $dates[$dateStr] = [
                'intensity' => $intensity,
                'label' => now()->subDays($i)->translatedFormat('d M'),
            ];
        }

        return [
            'dates' => $dates,
        ];
    }
}
