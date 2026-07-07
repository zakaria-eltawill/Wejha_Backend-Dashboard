<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository
{
    public function find(string $id): ?Notification
    {
        return Notification::find($id);
    }

    public function getPendingNotifications(): Collection
    {
        return Notification::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function update(Notification $notification, array $data): bool
    {
        return $notification->update($data);
    }
}
