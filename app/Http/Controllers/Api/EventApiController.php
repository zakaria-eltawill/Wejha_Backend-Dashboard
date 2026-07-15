<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventEvaluation;
use Illuminate\Http\JsonResponse;

class EventApiController extends Controller
{
    public function index(): JsonResponse
    {
        $events = Event::whereIn('status', ['published', 'archived'])->get();

        // Upcoming/ongoing events first (soonest first), then past events after (most recently ended first).
        $sorted = $events->sortBy(function (Event $event) {
            return $event->hasEnded()
                ? 99999999999 - $event->endsAt()->timestamp
                : $event->startsAt()->timestamp;
        })->values();

        return response()->json([
            'success' => true,
            'events' => $sorted->map(fn (Event $event) => $this->formatEvent($event)),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $event = Event::where('id', $id)->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'الفعالية غير موجودة / Event not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'event' => $this->formatEvent($event),
        ]);
    }

    private function formatEvent(Event $event): array
    {
        return [
            'id' => $event->id,
            'title_ar' => $event->title_ar,
            'title_en' => $event->title_en,
            'description_ar' => $event->description_ar,
            'description_en' => $event->description_en,
            'type' => $event->type,
            'speaker' => $event->speaker,
            'event_date' => $event->event_date,
            'event_time' => $event->event_time,
            'end_date' => $event->end_date,
            'end_time' => $event->end_time,
            'venue' => $event->venue,
            'capacity' => $event->capacity,
            'banner_image_url' => $event->banner_image ? asset('storage/' . $event->banner_image) : null,
            'cover_image_url' => $event->cover_image ? asset('storage/' . $event->cover_image) : null,
            'has_ended' => $event->hasEnded(),
            'can_register' => !$event->hasEnded(),
            'recording_url' => $event->hasEnded() ? $event->recording_url : null,
        ];
    }

    public function evaluations(string $id): JsonResponse
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'الفعالية غير موجودة / Event not found.'
            ], 404);
        }

        $evaluations = EventEvaluation::where('event_id', $id)
            ->where('is_active', true)
            ->with('template')
            ->get();

        return response()->json([
            'success' => true,
            'evaluations' => $evaluations->map(fn (EventEvaluation $evaluation) => [
                'id' => $evaluation->id,
                'evaluation_type' => $evaluation->evaluation_type,
                'is_active' => $evaluation->is_active,
                'template' => $evaluation->template ? [
                    'id' => $evaluation->template->id,
                    'name_ar' => $evaluation->template->name_ar,
                    'name_en' => $evaluation->template->name_en,
                    'status' => $evaluation->template->status,
                    'category' => $evaluation->template->category,
                ] : null,
            ]),
        ]);
    }
}
