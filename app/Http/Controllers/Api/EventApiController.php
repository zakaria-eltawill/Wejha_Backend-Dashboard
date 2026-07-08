<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class EventApiController extends Controller
{
    public function index(): JsonResponse
    {
        $now = \Carbon\Carbon::now(config('app.timezone', 'Africa/Tripoli'));
        $todayDate = $now->toDateString();
        $currentTime = $now->toTimeString();

        $events = Event::where('status', 'published')
            ->where(function ($query) use ($todayDate, $currentTime) {
                $query->where('event_date', '>', $todayDate)
                    ->orWhere(function ($q) use ($todayDate, $currentTime) {
                        $q->where('event_date', $todayDate)
                            ->where('event_time', '>=', $currentTime);
                    });
            })
            ->orderBy('event_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'events' => $events->map(fn ($event) => [
                'id' => $event->id,
                'title_ar' => $event->title_ar,
                'title_en' => $event->title_en,
                'description_ar' => $event->description_ar,
                'description_en' => $event->description_en,
                'type' => $event->type,
                'speaker' => $event->speaker,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'venue' => $event->venue,
                'capacity' => $event->capacity,
                'banner_image_url' => $event->banner_image ? asset('storage/' . $event->banner_image) : null,
                'cover_image_url' => $event->cover_image ? asset('storage/' . $event->cover_image) : null,
            ])
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
            'event' => [
                'id' => $event->id,
                'title_ar' => $event->title_ar,
                'title_en' => $event->title_en,
                'description_ar' => $event->description_ar,
                'description_en' => $event->description_en,
                'type' => $event->type,
                'speaker' => $event->speaker,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'venue' => $event->venue,
                'capacity' => $event->capacity,
                'banner_image_url' => $event->banner_image ? asset('storage/' . $event->banner_image) : null,
                'cover_image_url' => $event->cover_image ? asset('storage/' . $event->cover_image) : null,
            ]
        ]);
    }
}
