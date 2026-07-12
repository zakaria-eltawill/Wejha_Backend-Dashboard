<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Registration::where('user_id', auth()->id())
            ->with(['event', 'attendance'])
            ->orderBy('registered_at', 'desc');

        if ($request->query('status') === 'upcoming') {
            $query->whereHas('event', fn ($q) => $q->where('event_date', '>=', now()->toDateString()))
                ->where('status', '!=', 'cancelled');
        } elseif ($request->query('status') === 'past') {
            $query->where(function ($q) {
                $q->whereHas('event', fn ($eq) => $eq->where('event_date', '<', now()->toDateString()))
                    ->orWhere('status', 'cancelled');
            });
        }

        $registrations = $query->get();

        return response()->json([
            'success' => true,
            'registrations' => $registrations->map(fn ($registration) => $this->formatRegistration($registration)),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $registration = Registration::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['event', 'attendance'])
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'التذكرة غير موجودة أو لا تملك صلاحية الوصول إليها / Ticket not found or access denied.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'registration' => $this->formatRegistration($registration),
        ]);
    }

    private function formatRegistration(Registration $registration): array
    {
        $event = $registration->event;

        return [
            'id' => $registration->id,
            'status' => $registration->status,
            'qr_hash' => $registration->qr_hash,
            'source' => $registration->source,
            'registered_at' => $registration->registered_at,
            'checked_in_at' => $registration->attendance?->scan_time,
            'event' => $event ? [
                'id' => $event->id,
                'title_ar' => $event->title_ar,
                'title_en' => $event->title_en,
                'type' => $event->type,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'venue' => $event->venue,
                'banner_image_url' => $event->banner_image ? asset('storage/' . $event->banner_image) : null,
            ] : null,
        ];
    }
}
