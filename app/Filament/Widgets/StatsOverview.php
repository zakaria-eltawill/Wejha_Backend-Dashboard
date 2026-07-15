<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $analytics = app(AnalyticsService::class);
        $stats = $analytics->getDashboardStats();

        // Count upcoming events
        $upcomingCount = Event::where('status', 'published')
            ->whereDate('event_date', '>=', now())
            ->count();

        return [
            Stat::make(__('filament-widgets.stats_overview.total_events'), (string) $stats['total_events'])
                ->description(__('filament-widgets.stats_overview.total_events_desc'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->chart([7, 3, 5, 2, 8, 9, 10]),

            Stat::make(__('filament-widgets.stats_overview.active_events'), (string) $stats['active_events'])
                ->description(__('filament-widgets.stats_overview.active_events_desc'))
                ->descriptionIcon('heroicon-m-play')
                ->color('success')
                ->chart([3, 5, 4, 6, 8, 7, 10]),

            Stat::make(__('filament-widgets.stats_overview.total_registrations'), (string) $stats['total_registrations'])
                ->description(__('filament-widgets.stats_overview.total_registrations_desc'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('secondary')
                ->chart([15, 22, 35, 40, 52, 60, 72]),

            Stat::make(__('filament-widgets.stats_overview.attendance_rate'), $stats['attendance_rate'] . '%')
                ->description(__('filament-widgets.stats_overview.attendance_rate_desc'))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($stats['attendance_rate'] > 75 ? 'success' : 'warning')
                ->chart([50, 60, 68, 70, 72, 74, (int)$stats['attendance_rate']]),

            Stat::make(__('filament-widgets.stats_overview.survey_completion'), $stats['survey_completion_rate'] . '%')
                ->description(__('filament-widgets.stats_overview.survey_completion_desc'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color($stats['survey_completion_rate'] > 50 ? 'success' : 'danger')
                ->chart([20, 30, 42, 45, 48, 50, (int)$stats['survey_completion_rate']]),

            Stat::make(__('filament-widgets.stats_overview.active_users'), (string) $stats['active_users'])
                ->description(__('filament-widgets.stats_overview.active_users_desc'))
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([100, 120, 150, 180, 200, 220, (int)$stats['active_users']]),

            Stat::make(__('filament-widgets.stats_overview.participating_schools'), (string) $stats['schools_count'])
                ->description(__('filament-widgets.stats_overview.participating_schools_desc'))
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('accent')
                ->chart([5, 8, 12, 14, 18, 20, (int)$stats['schools_count']]),

            Stat::make(__('filament-widgets.stats_overview.upcoming_events'), (string) $upcomingCount)
                ->description(__('filament-widgets.stats_overview.upcoming_events_desc'))
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('warning')
                ->chart([2, 3, 4, 3, 5, 4, $upcomingCount]),
        ];
    }
}
