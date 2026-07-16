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

        $logoPath = public_path('assets/logo/wejha_logo_vertical_multi_gradient_transparent.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $html = view('reports.attendance', [
            'event' => $event,
            'title_ar' => 'تقرير حضور الفعالية: ' . $event->title_ar,
            'title_en' => 'Event Attendance Report: ' . $event->title_en,
            'generated_at' => now(),
            'logo_base64' => $logoBase64,
        ])->render();

        $shapedHtml = $this->shapeHtml($html);

        $pdf = Pdf::loadHTML($shapedHtml);

        return $pdf->output();
    }

    public function generateSurveyResponsePdf(string $evaluationId): string
    {
        $evaluation = \App\Models\EventEvaluation::with(['event', 'template.questions', 'responses.user', 'responses.question'])->findOrFail($evaluationId);

        $logoPath = public_path('assets/logo/wejha_logo_vertical_multi_gradient_transparent.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        $html = view('reports.survey_responses', [
            'evaluation' => $evaluation,
            'title_ar' => 'تقرير تقييم الفعالية: ' . $evaluation->event->title_ar,
            'title_en' => 'Event Survey Report: ' . $evaluation->event->title_en,
            'generated_at' => now(),
            'logo_base64' => $logoBase64,
        ])->render();

        $shapedHtml = $this->shapeHtml($html);

        $pdf = Pdf::loadHTML($shapedHtml);

        return $pdf->output();
    }

    /**
     * Shape Arabic text within HTML text nodes to prevent DomPDF RTL/disconnected text issues.
     */
    protected function shapeHtml(string $html): string
    {
        $arabic = new \ArPHP\I18N\Arabic('Glyphs');
        
        libxml_use_internal_errors(true);
        
        $dom = new \DOMDocument();
        // Load HTML with utf-8 encoding declaration
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        $xpath = new \DOMXPath($dom);
        // Extract text nodes excluding style/script blocks and <title> — the <title> tag becomes
        // the PDF's Document Info "Title" metadata, which PDF viewers render with their own correct
        // bidi algorithm; shaping it (which is only needed to work around dompdf's own broken RTL
        // canvas rendering) makes it display garbled/reversed there instead of fixing anything.
        $textNodes = $xpath->query('//text()[not(parent::style or parent::script or parent::title)]');
        
        foreach ($textNodes as $node) {
            $text = $node->nodeValue;
            if (preg_match('/[\x{0600}-\x{06FF}]/u', $text)) {
                $node->nodeValue = $arabic->utf8Glyphs($text);
            }
        }
        
        $shapedHtml = $dom->saveHTML();
        libxml_clear_errors();
        
        return str_replace('<?xml encoding="utf-8" ?>', '', $shapedHtml);
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
