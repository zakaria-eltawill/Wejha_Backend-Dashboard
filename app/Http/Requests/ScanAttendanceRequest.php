<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qr_hash' => ['required', 'string', 'max:255'],
            'device' => ['nullable', 'string', 'max:255'],
            'event_id' => ['nullable', 'string', 'exists:events,id'],
        ];
    }
}
