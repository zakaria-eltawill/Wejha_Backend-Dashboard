<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromQuery, WithHeadings, WithTitle, WithMapping
{
    public function __construct(
        protected string $eventId
    ) {}

    public function query()
    {
        return Attendance::query()
            ->join('registrations', 'attendance.registration_id', '=', 'registrations.id')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->where('registrations.event_id', $this->eventId)
            ->select('attendance.*', 'users.name as user_name', 'users.email as user_email', 'users.phone_number as user_phone');
    }

    public function title(): string
    {
        return 'Attendance Report';
    }

    public function headings(): array
    {
        return [
            'Attendance ID',
            'Participant Name',
            'Participant Email',
            'Participant Phone',
            'Checked-In At',
            'Device',
            'IP Address',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user_name,
            $row->user_email,
            $row->user_phone,
            $row->scan_time->toDateTimeString(),
            $row->device,
            $row->ip_address,
        ];
    }
}
