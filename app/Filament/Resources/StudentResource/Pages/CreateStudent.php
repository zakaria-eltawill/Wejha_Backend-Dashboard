<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function afterCreate(): void
    {
        // This resource only ever manages students — the Student role is implicit,
        // not exposed as a picker on the form (unlike UserResource for staff).
        $this->record->assignRole('Student');
    }
}
