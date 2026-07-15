<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;

class EventRegistrationsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '250px';

    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return __('filament-widgets.event_registrations.heading');
    }

    protected function getData(): array
    {
        return app(AnalyticsService::class)->getRegistrationTrendsData();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
