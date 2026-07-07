<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;

class EventRegistrationsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'تسجيلات الفعاليات / Event Registrations';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        return app(AnalyticsService::class)->getRegistrationTrendsData();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
