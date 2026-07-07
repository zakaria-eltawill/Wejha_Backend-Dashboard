<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;

class AttendanceRepository
{
    public function find(string $id): ?Attendance
    {
        return Attendance::find($id);
    }

    public function findByRegistration(string $registrationId): ?Attendance
    {
        return Attendance::where('registration_id', $registrationId)->first();
    }

    public function create(array $data): Attendance
    {
        return Attendance::create($data);
    }
}
