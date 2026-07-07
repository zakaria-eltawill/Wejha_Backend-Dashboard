<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitSurveyRequest;
use App\Services\SurveyResponseService;
use Illuminate\Http\JsonResponse;

class SurveyController extends Controller
{
    public function __construct(
        protected SurveyResponseService $surveyResponseService
    ) {}

    public function submit(string $evaluationId, SubmitSurveyRequest $request): JsonResponse
    {
        try {
            $responses = $this->surveyResponseService->submitResponses(
                auth()->id(),
                $evaluationId,
                $request->input('answers')
            );

            return response()->json([
                'success' => true,
                'message' => 'تم تقديم إجابات الاستبيان بنجاح! شكرًا لمشاركتك.',
                'response_count' => count($responses)
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 400);
        }
    }
}
