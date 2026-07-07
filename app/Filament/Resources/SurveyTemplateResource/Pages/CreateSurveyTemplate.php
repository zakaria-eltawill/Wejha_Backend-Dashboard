<?php

declare(strict_types=1);

namespace App\Filament\Resources\SurveyTemplateResource\Pages;

use App\Filament\Resources\SurveyTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyTemplate extends CreateRecord
{
    protected static string $resource = SurveyTemplateResource::class;
}
