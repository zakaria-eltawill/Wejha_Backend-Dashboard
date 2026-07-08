<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\DuplicateRegistrationException;
use App\Exceptions\InvalidQRCodeException;
use App\Enums\RegistrationStatus;
use App\Models\Attendance;
use App\Models\Registration;
use App\Repositories\AttendanceRepository;
use App\Repositories\RegistrationRepository;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        protected RegistrationRepository $registrationRepository,
        protected AttendanceRepository $attendanceRepository,
        protected EventService $eventService
    ) {}

    public function recordAttendance(
        string $qrHash,
        ?string $scannerUserId = null,
        ?string $device = null,
        ?string $ipAddress = null,
        ?string $eventId = null
    ): Attendance {
        return DB::transaction(function () use ($qrHash, $scannerUserId, $device, $ipAddress, $eventId) {
            // 1. Validate QR Hash
            $registration = $this->registrationRepository->findByQrHash($qrHash);
            if (!$registration) {
                throw new InvalidQRCodeException('Invalid QR ticket hash.');
            }

            if ($eventId && $registration->event_id !== $eventId) {
                throw new InvalidQRCodeException('تذكرة خاطئة: هذه التذكرة تابعة لفعالية أخرى / Invalid ticket: This ticket belongs to another event.');
            }

            // 2. Check Event status and dates (optional check)
            $event = $registration->event;

            // 3. Prevent duplicate attendance
            if ($registration->status === RegistrationStatus::CHECKED_IN) {
                $existing = $this->attendanceRepository->findByRegistration($registration->id);
                $prevTime = $existing ? $existing->scan_time->toIso8601String() : 'unknown';
                $prevOperator = $existing && $existing->scannerUser ? $existing->scannerUser->name : 'system';
                throw new DuplicateRegistrationException(
                    "Already checked in at {$prevTime} by {$prevOperator}."
                );
            }

            if ($registration->status !== RegistrationStatus::APPROVED) {
                throw new \InvalidArgumentException('Registration is not approved.');
            }

            // 4. Update Registration Status
            $registration->status = RegistrationStatus::CHECKED_IN;
            $registration->save();

            // 5. Create Attendance Record
            $attendance = $this->attendanceRepository->create([
                'registration_id' => $registration->id,
                'scanner_user_id' => $scannerUserId,
                'scan_time' => now(),
                'device' => $device,
                'ip_address' => $ipAddress,
            ]);

            // 6. Log to event activity timeline
            $studentName = $registration->user->name;
            $this->eventService->logActivity(
                $event->id,
                "تم تسجيل حضور المشارك: {$studentName}",
                "Checked in participant: {$studentName}",
                'qr_checked_in'
            );

            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');

            return $attendance;
        });
    }
}
