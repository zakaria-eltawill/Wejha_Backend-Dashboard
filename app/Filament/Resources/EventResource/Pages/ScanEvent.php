<?php

declare(strict_types=1);

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ScanEvent extends Page
{
    use InteractsWithRecord;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.pages.scan-event';

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return __('filament-events.pages.scan.heading_prefix') . ($this->record->title_ar ?? $this->record->title_en);
    }

    public function getHeading(): string
    {
        return __('filament-events.pages.scan.heading_prefix') . ($this->record->title_ar ?? $this->record->title_en);
    }
}
