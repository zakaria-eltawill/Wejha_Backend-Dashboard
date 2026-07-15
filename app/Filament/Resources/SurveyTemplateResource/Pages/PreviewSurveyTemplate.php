<?php

declare(strict_types=1);

namespace App\Filament\Resources\SurveyTemplateResource\Pages;

use App\Filament\Resources\SurveyTemplateResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class PreviewSurveyTemplate extends Page
{
    use InteractsWithRecord;

    protected static string $resource = SurveyTemplateResource::class;

    protected static string $view = 'filament.resources.survey-template-resource.pages.preview-survey-template';

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->load(['questions' => fn ($query) => $query->orderBy('sort_order')]);
    }

    public function getTitle(): string
    {
        return __('filament-surveys.preview.title');
    }

    public function getHeading(): string
    {
        return __('filament-surveys.preview.title');
    }

    public function getSubheading(): ?string
    {
        return __('filament-surveys.preview.subheading');
    }
}
