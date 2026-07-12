<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\EvaluationType;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitSurveyRequest;
use App\Models\EventEvaluation;
use App\Models\Registration;
use App\Models\SurveyResponse;
use App\Services\SurveyResponseService;
use Illuminate\Http\JsonResponse;

class SurveyController extends Controller
{
    public function __construct(
        protected SurveyResponseService $surveyResponseService
    ) {}

    public function questions(string $evaluationId): JsonResponse
    {
        $evaluation = EventEvaluation::with(['template.questions' => fn ($q) => $q->orderBy('sort_order')])
            ->find($evaluationId);

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'جلسة الاستبيان غير موجودة / Evaluation session not found.'
            ], 404);
        }

        if (!$evaluation->is_active || $evaluation->template->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'هذا الاستبيان مغلق حالياً / This evaluation survey is currently closed.'
            ], 403);
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('event_id', $evaluation->event_id)
            ->first();

        $canSubmit = true;
        $reason = null;

        if (!$registration) {
            $canSubmit = false;
            $reason = 'المستخدم غير مسجل في هذه الفعالية / User is not registered for this event.';
        } elseif ($evaluation->evaluation_type === EvaluationType::POST
            && $registration->status !== RegistrationStatus::CHECKED_IN) {
            $canSubmit = false;
            $reason = 'يجب تسجيل الحضور أولاً قبل تقديم استبيان ما بعد الفعالية / Must check in before taking the post-assessment.';
        }

        $alreadySubmitted = SurveyResponse::where('user_id', auth()->id())
            ->where('event_evaluation_id', $evaluationId)
            ->exists();

        return response()->json([
            'success' => true,
            'evaluation' => [
                'id' => $evaluation->id,
                'event_id' => $evaluation->event_id,
                'evaluation_type' => $evaluation->evaluation_type,
            ],
            'already_submitted' => $alreadySubmitted,
            'can_submit' => $canSubmit && !$alreadySubmitted,
            'reason' => $reason,
            'questions' => $evaluation->template->questions->map(fn ($question) => [
                'id' => $question->id,
                'type' => $question->type,
                'question_text_ar' => $question->question_text_ar,
                'question_text_en' => $question->question_text_en,
                'description_ar' => $question->description_ar,
                'description_en' => $question->description_en,
                'help_text_ar' => $question->help_text_ar,
                'help_text_en' => $question->help_text_en,
                'options' => $question->options,
                'is_required' => $question->is_required,
                'sort_order' => $question->sort_order,
            ]),
        ]);
    }

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
