<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\DuplicateRegistrationException;
use App\Exceptions\EventCapacityExceededException;
use App\Enums\RegistrationStatus;
use App\Models\Event;
use App\Models\Registration;
use App\Repositories\RegistrationRepository;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationService
{
    public function __construct(
        protected RegistrationRepository $registrationRepository,
        protected EventRepository $eventRepository,
        protected EventService $eventService
    ) {}

    public function register(string $userId, string $eventId, string $source = 'web'): Registration
    {
        return DB::transaction(function () use ($userId, $eventId, $source) {
            $event = $this->eventRepository->find($eventId);
            if (!$event) {
                throw new \InvalidArgumentException('Event not found.');
            }

            // 1. Check Capacity
            $registeredCount = Registration::where('event_id', $eventId)
                ->whereIn('status', [RegistrationStatus::APPROVED->value, RegistrationStatus::CHECKED_IN->value, RegistrationStatus::PENDING->value])
                ->count();

            if ($registeredCount >= $event->capacity) {
                throw new EventCapacityExceededException('Event is fully booked.');
            }

            // 2. Check Dates
            $now = now();
            if ($event->registration_opens_at && $now->lt($event->registration_opens_at)) {
                throw new \InvalidArgumentException('Registration is not open yet.');
            }
            if ($event->registration_closes_at && $now->gt($event->registration_closes_at)) {
                throw new \InvalidArgumentException('Registration has closed.');
            }

            // 3. Check Duplicate
            $existing = $this->registrationRepository->findByUserAndEvent($userId, $eventId);
            if ($existing) {
                throw new DuplicateRegistrationException('User is already registered for this event.');
            }

            // 4. Generate QR Hash
            $qrHash = Str::random(40);

            // 5. Determine Status
            $status = $event->requires_approval ? RegistrationStatus::PENDING : RegistrationStatus::APPROVED;

            // 6. Create Registration
            $registration = $this->registrationRepository->create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'qr_hash' => $qrHash,
                'source' => $source,
                'status' => $status->value,
                'registered_at' => now(),
            ]);

            // 7. Log to event activity timeline
            $statusStrAr = $status === RegistrationStatus::APPROVED ? 'مقبول تلقائياً' : 'قيد الانتظار للمراجعة';
            $statusStrEn = $status === RegistrationStatus::APPROVED ? 'Approved automatically' : 'Pending review';
            $this->eventService->logActivity(
                $eventId,
                "تم تسجيل مشارك جديد ($statusStrAr)",
                "New participant registered ($statusStrEn)",
                'participant_registered'
            );

            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');

            return $registration;
        });
    }

    public function cancel(string $registrationId): bool
    {
        $registration = $this->registrationRepository->find($registrationId);
        if (!$registration) {
            return false;
        }

        $registration->status = RegistrationStatus::CANCELLED;
        $saved = $registration->save();

        if ($saved) {
            $this->eventService->logActivity(
                $registration->event_id,
                'تم إلغاء تسجيل مشارك',
                'Participant registration cancelled',
                'registration_cancelled'
            );

            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        }

        return $saved;
    }
}
