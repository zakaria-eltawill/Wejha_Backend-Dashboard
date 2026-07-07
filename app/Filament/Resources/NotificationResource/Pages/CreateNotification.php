<?php

declare(strict_types=1);

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use App\Services\NotificationService;
use Filament\Resources\Pages\CreateRecord;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        if (!$record->scheduled_at) {
            app(NotificationService::class)->sendNotification($record);
        } else {
            $record->status = 'scheduled';
            $record->save();
        }
    }
}
