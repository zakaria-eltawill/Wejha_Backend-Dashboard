<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Registration;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    public function getRegistrationStats(): array
    {
        $total = Registration::count();
        $approved = Registration::where('status', 'approved')->count();
        $pending = Registration::where('status', 'pending')->count();
        $checkedIn = Registration::where('status', 'checked_in')->count();
        $cancelled = Registration::where('status', 'cancelled')->count();

        return [
            'total' => $total,
            'approved' => $approved,
            'pending' => $pending,
            'checked_in' => $checkedIn,
            'cancelled' => $cancelled,
        ];
    }

    public function getAttendanceRate(): float
    {
        $totalApproved = Registration::whereIn('status', ['approved', 'checked_in'])->count();
        if ($totalApproved === 0) {
            return 0.0;
        }

        $attended = Attendance::count();
        return round(($attended / $totalApproved) * 100, 2);
    }

    public function getTopSchools(int $limit = 5): array
    {
        return User::select('school_name', DB::raw('count(*) as participant_count'))
            ->whereNotNull('school_name')
            ->groupBy('school_name')
            ->orderByDesc('participant_count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getEventDistribution(): array
    {
        return Event::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();
    }

    public function getRegistrationsOverTime(): array
    {
        return Registration::select(
            DB::raw("TO_CHAR(registered_at, 'YYYY-MM-DD') as date"),
            DB::raw('count(*) as count')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    public function getSatisfactionTrends(): array
    {
        // Calculate average rating from survey questions of type 'rating'
        return SurveyResponse::join('survey_questions', 'survey_responses.question_id', '=', 'survey_questions.id')
            ->where('survey_questions.type', 'rating')
            ->select(
                DB::raw("TO_CHAR(survey_responses.submitted_at, 'YYYY-MM-DD') as date"),
                DB::raw('AVG(CAST(survey_responses.response_text AS DECIMAL)) as avg_rating')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('avg_rating', 'date')
            ->toArray();
    }

    public function getMonthlyActivityHeatmap(): array
    {
        return Event::select(
            DB::raw("TO_CHAR(event_date, 'YYYY-MM-DD') as date"),
            DB::raw('count(*) as intensity')
        )
            ->groupBy('date')
            ->get()
            ->pluck('intensity', 'date')
            ->toArray();
    }
}
