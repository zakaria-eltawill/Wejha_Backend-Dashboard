<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EventType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Handled by policies
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'type' => ['required', new Enum(EventType::class)],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['required', 'date_format:H:i'],
            'venue' => ['required', 'string', 'max:255'],
            'venue_map_url' => ['nullable', 'url'],
            'capacity' => ['required', 'integer', 'min:1'],
            'registration_opens_at' => ['nullable', 'date', 'before:event_date'],
            'registration_closes_at' => ['nullable', 'date', 'after:registration_opens_at', 'before_or_equal:event_date'],
            'qr_attendance_enabled' => ['boolean'],
            'requires_approval' => ['boolean'],
            'visibility' => ['string', 'in:public,private'],
            'featured' => ['boolean'],
            'organizer_notes' => ['nullable', 'string'],
            'contact_person' => ['nullable', 'string', 'max:255'],
        ];
    }
}
