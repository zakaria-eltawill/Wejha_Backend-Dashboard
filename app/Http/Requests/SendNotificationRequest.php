<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'content_ar' => ['required', 'string'],
            'content_en' => ['required', 'string'],
            'recipient_type' => ['required', 'string', 'in:individual,role,event,all'],
            'user_id' => ['required_if:recipient_type,individual', 'nullable', 'uuid', 'exists:users,id'],
            'role_id' => ['required_if:recipient_type,role', 'nullable', 'integer', 'exists:roles,id'],
            'event_id' => ['required_if:recipient_type,event', 'nullable', 'uuid', 'exists:events,id'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
