<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ReportRepository;
use App\Exports\AttendanceExport;
use App\Exports\RegistrationExport;
use App\Exports\SurveyResponseExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportService
{
    public function __construct(
        protected ReportRepository $reportRepository
    ) {}

    public function generateAttendancePdf(string $eventId): string
    {
        // Fetch registrations for event
        $event = \App\Models\Event::with(['registrations.user', 'registrations.attendance'])->findOrFail($eventId);

        $pdf = Pdf::loadView('reports.attendance', [
            'event' => $event,
            'title_ar' => 'تقرير حضور الفعالية: ' . $event->title_ar,
            'title_en' => 'Event Attendance Report: ' . $event->title_en,
            'generated_at' => now(),
        ]);

        return $pdf->output();
    }

    public function generateSurveyResponsePdf(string $evaluationId): string
    {
        $evaluation = \App\Models\EventEvaluation::with(['event', 'template.questions', 'responses.user', 'responses.question'])->findOrFail($evaluationId);

        $pdf = Pdf::loadView('reports.survey_responses', [
            'evaluation' => $evaluation,
            'title_ar' => 'تقرير تقييم الفعالية: ' . $evaluation->event->title_ar,
            'title_en' => 'Event Survey Report: ' . $evaluation->event->title_en,
            'generated_at' => now(),
        ]);

        return $pdf->output();
    }

    public function exportAttendanceExcel(string $eventId): BinaryFileResponse
    {
        return Excel::download(new AttendanceExport($eventId), "attendance_{$eventId}.xlsx");
    }

    public function exportRegistrationExcel(string $eventId): BinaryFileResponse
    {
        return Excel::download(new RegistrationExport($eventId), "registrations_{$eventId}.xlsx");
    }

    public function exportSurveyResponseExcel(string $evaluationId): BinaryFileResponse
    {
        return Excel::download(new SurveyResponseExport($evaluationId), "survey_responses_{$evaluationId}.xlsx");
    }
}
