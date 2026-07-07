<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use App\Models\SurveyTemplate;
use App\Models\SurveyQuestion;
use App\Models\EventEvaluation;
use App\Enums\RegistrationStatus;
use App\Enums\QuestionType;
use App\Enums\EvaluationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SurveySubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected Event $event;
    protected Registration $registration;
    protected SurveyTemplate $template;
    protected SurveyQuestion $question;
    protected EventEvaluation $preEvaluation;
    protected EventEvaluation $postEvaluation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create();

        $this->event = Event::create([
            'title_ar' => 'فعالية اختبار الاستبيان',
            'title_en' => 'Survey Test Event',
            'type' => 'workshop',
            'event_date' => now()->format('Y-m-d'),
            'event_time' => '14:00',
            'venue' => 'مختبر ب',
            'capacity' => 10,
            'status' => 'published',
            'creator_id' => $this->student->id,
        ]);

        $this->registration = Registration::create([
            'user_id' => $this->student->id,
            'event_id' => $this->event->id,
            'qr_hash' => Str::random(40),
            'status' => RegistrationStatus::APPROVED->value,
            'source' => 'web',
            'registered_at' => now(),
        ]);

        // Create survey template
        $this->template = SurveyTemplate::create([
            'name_ar' => 'نموذج تقييم',
            'name_en' => 'Assessment Template',
            'status' => 'active',
        ]);

        $this->question = SurveyQuestion::create([
            'survey_template_id' => $this->template->id,
            'type' => QuestionType::TEXT->value,
            'question_text_ar' => 'ما هي توقعاتك؟',
            'question_text_en' => 'What are your expectations?',
            'is_required' => true,
        ]);

        // Pre evaluation session
        $this->preEvaluation = EventEvaluation::create([
            'event_id' => $this->event->id,
            'survey_template_id' => $this->template->id,
            'evaluation_type' => EvaluationType::PRE->value,
            'is_active' => true,
        ]);

        // Post evaluation session
        $this->postEvaluation = EventEvaluation::create([
            'event_id' => $this->event->id,
            'survey_template_id' => $this->template->id,
            'evaluation_type' => EvaluationType::POST->value,
            'is_active' => true,
        ]);
    }

    public function test_registered_user_can_submit_pre_assessment_successfully(): void
    {
        $response = $this->actingAs($this->student)->postJson(route('api.surveys.submit', $this->preEvaluation->id), [
            'answers' => [
                [
                    'question_id' => $this->question->id,
                    'response_text' => 'أتوقع تعلم الكثير.',
                ]
            ]
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('survey_responses', [
            'user_id' => $this->student->id,
            'event_evaluation_id' => $this->preEvaluation->id,
            'question_id' => $this->question->id,
            'response_text' => 'أتوقع تعلم الكثير.',
        ]);
    }

    public function test_user_cannot_submit_post_assessment_without_attendance(): void
    {
        // Try submitting post-assessment while status is approved (not checked_in/attended)
        $response = $this->actingAs($this->student)->postJson(route('api.surveys.submit', $this->postEvaluation->id), [
            'answers' => [
                [
                    'question_id' => $this->question->id,
                    'response_text' => 'كانت ورشة مفيدة.',
                ]
            ]
        ]);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
    }

    public function test_attended_user_can_submit_post_assessment_successfully(): void
    {
        // Mark student as checked-in/attended
        $this->registration->status = RegistrationStatus::CHECKED_IN->value;
        $this->registration->save();

        $response = $this->actingAs($this->student)->postJson(route('api.surveys.submit', $this->postEvaluation->id), [
            'answers' => [
                [
                    'question_id' => $this->question->id,
                    'response_text' => 'كانت ورشة عمل رائعة!',
                ]
            ]
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
    }
}
