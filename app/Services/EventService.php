<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use App\Models\EventActivity;
use App\Repositories\EventRepository;

class EventService
{
    public function __construct(
        protected EventRepository $eventRepository
    ) {}

    public function createEvent(array $data, string $creatorId): Event
    {
        $data['creator_id'] = $creatorId;
        $event = $this->eventRepository->create($data);

        $this->logActivity(
            $event->id,
            'تم إنشاء الفعالية بنجاح',
            'Event created successfully',
            'event_created'
        );

        return $event;
    }

    public function updateEvent(Event $event, array $data): bool
    {
        $updated = $this->eventRepository->update($event, $data);
        if ($updated) {
            $this->logActivity(
                $event->id,
                'تم تحديث تفاصيل الفعالية',
                'Event details updated',
                'event_updated'
            );
        }
        return $updated;
    }

    public function logActivity(string $eventId, string $descAr, string $descEn, string $type): EventActivity
    {
        return EventActivity::create([
            'event_id' => $eventId,
            'description_ar' => $descAr,
            'description_en' => $descEn,
            'type' => $type,
            'occurred_at' => now(),
        ]);
    }
}
