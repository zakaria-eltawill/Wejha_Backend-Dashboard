<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\SurveyResponse;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyResponseExport implements FromQuery, WithHeadings, WithTitle, WithMapping
{
    public function __construct(
        protected string $evaluationId
    ) {}

    public function query()
    {
        return SurveyResponse::query()
            ->join('users', 'survey_responses.user_id', '=', 'users.id')
            ->join('survey_questions', 'survey_responses.question_id', '=', 'survey_questions.id')
            ->where('survey_responses.event_evaluation_id', $this->evaluationId)
            ->select('survey_responses.*', 'users.name as user_name', 'survey_questions.question_text_ar as question_text');
    }

    public function title(): string
    {
        return 'Survey Responses Report';
    }

    public function headings(): array
    {
        return [
            'Response ID',
            'Participant Name',
            'Question text (AR)',
            'Response Text Value',
            'Response JSON Value',
            'Submitted At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user_name,
            $row->question_text,
            $row->response_text,
            $row->response_json ? json_encode($row->response_json, JSON_UNESCAPED_UNICODE) : null,
            $row->submitted_at->toDateTimeString(),
        ];
    }
}
