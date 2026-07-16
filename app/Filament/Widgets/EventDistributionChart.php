<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Repositories\ReportRepository;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class EventDistributionChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected static ?string $maxHeight = '260px';

    /**
     * Validated 3-slot categorical palette (Wejha navy / orange / teal), lightness-band
     * and CVD-checked via the dataviz skill validator (worst adjacent ΔE 37.2, well above
     * the ≥12 target) — see conversation for the exact validation command/output.
     */
    private const CATEGORY_COLORS = ['#2452C4', '#FF4900', '#0CA678'];

    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return __('filament-widgets.event_distribution.heading');
    }

    protected function getData(): array
    {
        $dist = app(ReportRepository::class)->getEventDistribution();

        $translatedData = [];
        $labels = [];

        foreach ($dist as $type => $count) {
            $labels[] = match ($type) {
                'seminar' => __('filament-widgets.event_distribution.seminar'),
                'workshop' => __('filament-widgets.event_distribution.workshop'),
                'exhibition' => __('filament-widgets.event_distribution.exhibition'),
                default => $type
            };
            $translatedData[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('filament-widgets.event_distribution.dataset_label'),
                    'data' => $translatedData,
                    'backgroundColor' => array_slice(self::CATEGORY_COLORS, 0, count($labels)),
                    'borderColor' => '#fcfcfb',
                    'borderWidth' => 2,
                    'hoverOffset' => 6,
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array | RawJs
    {
        // maintainAspectRatio must be false here: Chart.js defaults to a fixed 1:1 aspect
        // ratio for doughnut/pie charts, which fights the widget's own fixed-height
        // container on every Livewire re-render and produces a runaway/overflowing canvas
        // (the exact "giant blob with stray axis lines" bug this replaces).
        return [
            'maintainAspectRatio' => false,
            'cutout' => '62%',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 16,
                        'font' => ['size' => 12],
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => RawJs::make(<<<'JS'
                            (context) => {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((sum, v) => sum + v, 0);
                                const pct = total > 0 ? Math.round((value / total) * 100) : 0;
                                return ` ${context.label}: ${value} (${pct}%)`;
                            }
                        JS),
                    ],
                ],
            ],
        ];
    }
}
