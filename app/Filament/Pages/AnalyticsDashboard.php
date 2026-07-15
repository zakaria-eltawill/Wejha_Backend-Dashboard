<?php

declare(strict_types=1);

namespace App\Filament\Pages;


use App\Models\Event;
use App\Models\EventEvaluation;
use App\Services\ReportService;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AnalyticsDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static string $view = 'filament.pages.analytics-dashboard';
    protected static ?int $navigationSort = 4;

    public ?string $event_id = null;
    public ?string $evaluation_id = null;

    public static function getNavigationLabel(): string
    {
        return __('filament-pages.analytics_dashboard.navigation_label');
    }

    public function getHeading(): string
    {
        return __('filament-pages.analytics_dashboard.heading');
    }

    public function mount(): void
    {
        // Load default event and evaluation
        $this->event_id = Event::first()?->id;
        $this->evaluation_id = EventEvaluation::first()?->id;
    }

    public function downloadAttendancePdf()
    {
        if (!$this->event_id) {
            Notification::make()->title('الرجاء اختيار فعالية أولاً')->danger()->send();
            return null;
        }

        $reportService = app(ReportService::class);
        $pdfContent = $reportService->generateAttendancePdf($this->event_id);

        return response()->streamDownload(
            fn () => print($pdfContent),
            "attendance_report_{$this->event_id}.pdf"
        );
    }

    public function downloadAttendanceExcel(): ?BinaryFileResponse
    {
        if (!$this->event_id) {
            Notification::make()->title('الرجاء اختيار فعالية أولاً')->danger()->send();
            return null;
        }

        return app(ReportService::class)->exportAttendanceExcel($this->event_id);
    }

    public function downloadSurveyPdf()
    {
        if (!$this->evaluation_id) {
            Notification::make()->title('الرجاء اختيار تقييم أولاً')->danger()->send();
            return null;
        }

        $reportService = app(ReportService::class);
        $pdfContent = $reportService->generateSurveyResponsePdf($this->evaluation_id);

        return response()->streamDownload(
            fn () => print($pdfContent),
            "survey_report_{$this->evaluation_id}.pdf"
        );
    }

    public function downloadSurveyExcel(): ?BinaryFileResponse
    {
        if (!$this->evaluation_id) {
            Notification::make()->title('الرجاء اختيار تقييم أولاً')->danger()->send();
            return null;
        }

        return app(ReportService::class)->exportSurveyResponseExcel($this->evaluation_id);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
        ];
    }
}
