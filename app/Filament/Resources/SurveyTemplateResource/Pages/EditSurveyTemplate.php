<?php

declare(strict_types=1);

namespace App\Filament\Resources\SurveyTemplateResource\Pages;

use App\Filament\Resources\SurveyTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyTemplate extends EditRecord
{
    protected static string $resource = SurveyTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
