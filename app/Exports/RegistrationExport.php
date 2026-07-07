<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrationExport implements FromQuery, WithHeadings, WithTitle, WithMapping
{
    public function __construct(
        protected string $eventId
    ) {}

    public function query()
    {
        return Registration::query()
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->where('registrations.event_id', $this->eventId)
            ->select('registrations.*', 'users.name as user_name', 'users.email as user_email', 'users.school_name as user_school');
    }

    public function title(): string
    {
        return 'Registrations Report';
    }

    public function headings(): array
    {
        return [
            'Registration ID',
            'Participant Name',
            'Participant Email',
            'School Name',
            'Status',
            'Source',
            'Registered At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->user_name,
            $row->user_email,
            $row->user_school,
            $row->status,
            $row->source,
            $row->registered_at->toDateTimeString(),
        ];
    }
}
