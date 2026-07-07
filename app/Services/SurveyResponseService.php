<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\SurveyClosedException;
use App\Enums\EvaluationType;
use App\Enums\RegistrationStatus;
use App\Models\EventEvaluation;
use App\Models\Registration;
use App\Repositories\SurveyRepository;
use App\Repositories\RegistrationRepository;
use Illuminate\Support\Facades\DB;

class SurveyResponseService
{
    public function __construct(
        protected SurveyRepository $surveyRepository,
        protected RegistrationRepository $registrationRepository,
        protected EventService $eventService
    ) {}

    public function submitResponses(string $userId, string $evaluationId, array $answers): array
    {
        return DB::transaction(function () use ($userId, $evaluationId, $answers) {
            $evaluation = $this->surveyRepository->findEvaluation($evaluationId);
            if (!$evaluation) {
                throw new \InvalidArgumentException('Evaluation session not found.');
            }

            if (!$evaluation->is_active || $evaluation->template->status !== 'active') {
                throw new SurveyClosedException('This evaluation survey is currently closed.');
            }

            // 1. Verify eligibility
            $event = $evaluation->event;
            $registration = $this->registrationRepository->findByUserAndEvent($userId, $event->id);

            if (!$registration) {
                throw new \InvalidArgumentException('User is not registered for this event.');
            }

            // Check pre vs post evaluation eligibility
            if ($evaluation->evaluation_type === EvaluationType::POST) {
                // Post-assessment requires attendance
                if ($registration->status !== RegistrationStatus::CHECKED_IN) {
                    throw new \InvalidArgumentException('User must check in (attend) before taking the post-assessment.');
                }
            }

            $savedResponses = [];

            // 2. Validate and Save Answers
            foreach ($answers as $answer) {
                $questionId = $answer['question_id'];
                $question = $this->surveyRepository->findQuestion($questionId);

                if (!$question || $question->survey_template_id !== $evaluation->survey_template_id) {
                    throw new \InvalidArgumentException("Question {$questionId} does not belong to this survey template.");
                }

                // Check required
                if ($question->is_required && empty($answer['response_text']) && empty($answer['response_json'])) {
                    throw new \InvalidArgumentException("Question {$questionId} is required.");
                }

                $responseText = $answer['response_text'] ?? null;
                if ($responseText !== null) {
                    $responseText = (string) $responseText;
                }

                $savedResponses[] = $this->surveyRepository->createResponse([
                    'user_id' => $userId,
                    'event_evaluation_id' => $evaluation->id,
                    'question_id' => $questionId,
                    'response_text' => $responseText,
                    'response_json' => $answer['response_json'] ?? null,
                    'submitted_at' => now(),
                ]);
            }

            // 3. Log activity
            $studentName = $registration->user->name;
            $evalTypeStr = $evaluation->evaluation_type->labelAr();
            $this->eventService->logActivity(
                $event->id,
                "قام المشارك ({$studentName}) بتقديم {$evalTypeStr}",
                "Participant ({$studentName}) submitted {$evaluation->evaluation_type->labelEn()}",
                'survey_submitted'
            );

            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');

            return $savedResponses;
        });
    }
}
