<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:20'],
            'gender' => ['sometimes', 'nullable', 'string', 'in:male,female'],
            'academic_year' => ['sometimes', 'nullable', 'string', 'max:50'],
            'school_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'specialization' => ['sometimes', 'nullable', 'string', 'in:scientific,literary,علمي,أدبي'],
            'avatar' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'preferred_language' => ['sometimes', 'string', 'in:ar,en'],
            'preferred_theme' => ['sometimes', 'string', 'in:light,dark,system'],
            'notification_preferences' => ['sometimes', 'array'],
        ];
    }
}
