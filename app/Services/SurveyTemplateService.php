<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SurveyQuestion;
use App\Models\SurveyTemplate;
use App\Repositories\SurveyRepository;
use Illuminate\Support\Facades\DB;

class SurveyTemplateService
{
    public function __construct(
        protected SurveyRepository $surveyRepository
    ) {}

    public function cloneTemplate(string $templateId, string $newSuffix = ' - نسخة'): SurveyTemplate
    {
        return DB::transaction(function () use ($templateId, $newSuffix) {
            $original = $this->surveyRepository->findTemplate($templateId);
            if (!$original) {
                throw new \InvalidArgumentException('Survey template not found.');
            }

            // 1. Replicate template
            $clone = $original->replicate(['created_at', 'updated_at']);
            $clone->name_ar = $original->name_ar . $newSuffix;
            $clone->name_en = $original->name_en . ' (Clone)';
            $clone->status = 'draft'; // Cloned is always draft
            $clone->save();

            // 2. Replicate questions
            foreach ($original->questions as $question) {
                $questionClone = $question->replicate(['created_at', 'updated_at', 'survey_template_id']);
                $questionClone->survey_template_id = $clone->id;
                $questionClone->save();
            }

            return $clone;
        });
    }

    public function createTemplateWithQuestions(array $templateData, array $questions): SurveyTemplate
    {
        return DB::transaction(function () use ($templateData, $questions) {
            $template = $this->surveyRepository->createTemplate($templateData);

            foreach ($questions as $questionData) {
                $questionData['survey_template_id'] = $template->id;
                $this->surveyRepository->createQuestion($questionData);
            }

            return $template;
        });
    }
}
