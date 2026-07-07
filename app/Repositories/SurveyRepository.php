<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EventEvaluation;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyTemplate;
use Illuminate\Database\Eloquent\Collection;

class SurveyRepository
{
    public function findTemplate(string $id): ?SurveyTemplate
    {
        return SurveyTemplate::find($id);
    }

    public function findQuestion(string $id): ?SurveyQuestion
    {
        return SurveyQuestion::find($id);
    }

    public function findEvaluation(string $id): ?EventEvaluation
    {
        return EventEvaluation::find($id);
    }

    public function findEvaluationByEventAndType(string $eventId, string $type): ?EventEvaluation
    {
        return EventEvaluation::where('event_id', $eventId)
            ->where('evaluation_type', $type)
            ->first();
    }

    public function createTemplate(array $data): SurveyTemplate
    {
        return SurveyTemplate::create($data);
    }

    public function createQuestion(array $data): SurveyQuestion
    {
        return SurveyQuestion::create($data);
    }

    public function createEvaluation(array $data): EventEvaluation
    {
        return EventEvaluation::create($data);
    }

    public function createResponse(array $data): SurveyResponse
    {
        return SurveyResponse::create($data);
    }

    public function getResponsesForEvaluation(string $evaluationId): Collection
    {
        return SurveyResponse::where('event_evaluation_id', $evaluationId)
            ->with(['user', 'question'])
            ->get();
    }

    public function getResponsesForUser(string $userId): Collection
    {
        return SurveyResponse::where('user_id', $userId)->get();
    }
}
