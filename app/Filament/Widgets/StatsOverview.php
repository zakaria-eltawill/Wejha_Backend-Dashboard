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
            Stat::make('إجمالي الفعاليات / Total Events', (string) $stats['total_events'])
                ->description('الفعاليات المجدولة والنشطة والمؤرشفة')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->chart([7, 3, 5, 2, 8, 9, 10]),

            Stat::make('الفعاليات النشطة / Active Events', (string) $stats['active_events'])
                ->description('الفعاليات الجارية والمقبلة حالياً')
                ->descriptionIcon('heroicon-m-play')
                ->color('success')
                ->chart([3, 5, 4, 6, 8, 7, 10]),

            Stat::make('إجمالي التسجيلات / Total Registrations', (string) $stats['total_registrations'])
                ->description('حجوزات الطلاب في كل الفعاليات')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('secondary')
                ->chart([15, 22, 35, 40, 52, 60, 72]),

            Stat::make('معدل الحضور / Attendance Rate', $stats['attendance_rate'] . '%')
                ->description('نسبة تحضير الطلاب الحاضرين بالـ QR')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($stats['attendance_rate'] > 75 ? 'success' : 'warning')
                ->chart([50, 60, 68, 70, 72, 74, (int)$stats['attendance_rate']]),

            Stat::make('معدل إكمال الاستبيان / Survey Completion', $stats['survey_completion_rate'] . '%')
                ->description('نسبة إكمال التقييمات القبلية والبعدية')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($stats['survey_completion_rate'] > 50 ? 'success' : 'danger')
                ->chart([20, 30, 42, 45, 48, 50, (int)$stats['survey_completion_rate']]),

            Stat::make('المستخدمون النشطون / Active Users', (string) $stats['active_users'])
                ->description('الطلاب والمشرفون والمنظمون الفاعلون')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([100, 120, 150, 180, 200, 220, (int)$stats['active_users']]),

            Stat::make('المدارس المشاركة / Participating Schools', (string) $stats['schools_count'])
                ->description('عدد المدارس الثانوية المسجلة')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('accent')
                ->chart([5, 8, 12, 14, 18, 20, (int)$stats['schools_count']]),

            Stat::make('الفعاليات المقبلة / Upcoming Events', (string) $upcomingCount)
                ->description('أحداث تنتظر بدء التسجيل أو التنفيذ')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('warning')
                ->chart([2, 3, 4, 3, 5, 4, $upcomingCount]),
        ];
    }
}
