<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Registration;

class RegistrationRepository
{
    public function find(string $id): ?Registration
    {
        return Registration::find($id);
    }

    public function findByUserAndEvent(string $userId, string $eventId): ?Registration
    {
        return Registration::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();
    }

    public function findByQrHash(string $qrHash): ?Registration
    {
        return Registration::where('qr_hash', $qrHash)
            ->with(['user', 'event'])
            ->first();
    }

    public function create(array $data): Registration
    {
        return Registration::create($data);
    }

    public function update(Registration $registration, array $data): bool
    {
        return $registration->update($data);
    }
}
