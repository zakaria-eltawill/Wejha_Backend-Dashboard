<?php

declare(strict_types=1);

namespace App\Filament\Resources\SurveyTemplateResource\Pages;

use App\Filament\Resources\SurveyTemplateResource;
use App\Services\SurveyTemplateService;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListSurveyTemplates extends ListRecords
{
    protected static string $resource = SurveyTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label(__('filament-surveys.actions.import'))
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->required()
                        ->acceptedFileTypes(['application/json'])
                        ->disk('public')
                        ->directory('imports'),
                ])
                ->action(function (array $data) {
                    $filePath = storage_path('app/public/' . $data['file']);
                    if (!file_exists($filePath)) {
                        return;
                    }
                    
                    $content = file_get_contents($filePath);
                    $decoded = json_decode($content, true);
                    
                    if (is_array($decoded)) {
                        $templateData = [
                            'name_ar' => $decoded['name_ar'] ?? 'نموذج مستورد',
                            'name_en' => $decoded['name_en'] ?? 'Imported Template',
                            'version' => $decoded['version'] ?? '1.0',
                            'status' => 'draft',
                            'category' => $decoded['category'] ?? null,
                            'is_reusable' => $decoded['is_reusable'] ?? true,
                            'description_ar' => $decoded['description_ar'] ?? null,
                            'description_en' => $decoded['description_en'] ?? null,
                        ];
                        
                        $questions = [];
                        foreach (($decoded['questions'] ?? []) as $q) {
                            $questions[] = [
                                'type' => $q['type'] ?? 'text',
                                'question_text_ar' => $q['question_text_ar'] ?? 'سؤال',
                                'question_text_en' => $q['question_text_en'] ?? 'Question',
                                'options' => $q['options'] ?? null,
                                'is_required' => $q['is_required'] ?? true,
                                'sort_order' => $q['sort_order'] ?? 0,
                            ];
                        }
                        
                        app(SurveyTemplateService::class)->createTemplateWithQuestions($templateData, $questions);
                    }
                }),
            Actions\CreateAction::make(),
        ];
    }
}
