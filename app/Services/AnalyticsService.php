<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Registration;
use App\Models\SurveyResponse;
use App\Models\User;
use App\Repositories\ReportRepository;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    public function __construct(
        protected ReportRepository $reportRepository
    ) {}

    public function getDashboardStats(): array
    {
        // Cache stats for 10 minutes to optimize database performance
        return Cache::remember('dashboard_stats', 600, function () {
            $totalEvents = Event::count();
            $activeEvents = Event::where('status', 'published')
                ->whereDate('event_date', '>=', now())
                ->count();
            $totalRegistrations = Registration::count();

            $attendanceRate = $this->reportRepository->getAttendanceRate();

            // Survey completion rate: total unique users who submitted responses divided by total attendance
            $attendedCount = Attendance::count();
            $surveyCompletersCount = SurveyResponse::distinct('user_id')->count('user_id');
            $surveyCompletionRate = $attendedCount > 0 
                ? round(($surveyCompletersCount / $attendedCount) * 100, 2)
                : 0.0;

            $activeUsers = User::where('status', 'active')->count();
            $schoolsCount = User::whereNotNull('school_name')->distinct('school_name')->count('school_name');

            return [
                'total_events' => $totalEvents,
                'active_events' => $activeEvents,
                'total_registrations' => $totalRegistrations,
                'attendance_rate' => $attendanceRate,
                'survey_completion_rate' => $surveyCompletionRate,
                'active_users' => $activeUsers,
                'schools_count' => $schoolsCount,
            ];
        });
    }

    public function getAttendanceChartData(): array
    {
        $registered = Registration::count();
        $attended = Attendance::count();
        $absent = max(0, $registered - $attended);

        return [
            'labels' => ['سجلوا حضورهم (حاضر)', 'غائبون (مسجلين لم يحضروا)'],
            'datasets' => [
                [
                    'label' => 'معدل الحضور',
                    'data' => [$attended, $absent],
                    'backgroundColor' => ['#001F8F', '#FF4900'],
                ]
            ]
        ];
    }

    public function getRegistrationTrendsData(): array
    {
        $trends = $this->reportRepository->getRegistrationsOverTime();
        return [
            'labels' => array_keys($trends),
            'datasets' => [
                [
                    'label' => 'التسجيلات اليومية',
                    'data' => array_values($trends),
                    'borderColor' => '#00389E',
                    'backgroundColor' => 'rgba(0, 56, 158, 0.1)',
                    'fill' => true,
                ]
            ]
        ];
    }

    public function getTopSchoolsChartData(): array
    {
        $schools = $this->reportRepository->getTopSchools();
        $labels = [];
        $data = [];

        foreach ($schools as $school) {
            $labels[] = $school['school_name'];
            $data[] = $school['participant_count'];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'عدد المشاركين',
                    'data' => $data,
                    'backgroundColor' => '#FF4900',
                ]
            ]
        ];
    }
}
