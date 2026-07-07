<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'uuid', 'exists:survey_questions,id'],
            'answers.*.response_text' => ['nullable', 'string'],
            'answers.*.response_json' => ['nullable', 'array'],
        ];
    }
}
