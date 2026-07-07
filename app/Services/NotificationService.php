<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use App\Models\User;
use App\Notifications\GenericNotification; // We'll create this class
use Illuminate\Support\Facades\Notification as FacadesNotification;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $notificationRepository
    ) {}

    public function createNotification(array $data): Notification
    {
        return $this->notificationRepository->create($data);
    }

    public function sendNotification(Notification $notification): void
    {
        $notification->status = 'processing';
        $notification->save();

        try {
            $recipients = $this->resolveRecipients($notification);

            if ($recipients->isNotEmpty()) {
                // Send notification using Laravel's notification system (which can be queued)
                FacadesNotification::send($recipients, new GenericNotification($notification));
            }

            $notification->status = 'sent';
            $notification->delivered_at = now();
            $notification->delivery_logs = [
                'recipient_count' => $recipients->count(),
                'success' => true,
            ];
            $notification->save();
        } catch (\Throwable $e) {
            $notification->status = 'failed';
            $notification->delivery_logs = [
                'error' => $e->getMessage(),
                'success' => false,
            ];
            $notification->save();
        }
    }

    protected function resolveRecipients(Notification $notification)
    {
        return match ($notification->recipient_type) {
            'individual' => User::where('id', $notification->user_id)->get(),
            'role' => User::role($notification->role_id)->get(),
            'event' => User::whereHas('registrations', function ($q) use ($notification) {
                $q->where('event_id', $notification->event_id);
            })->get(),
            'all' => User::where('status', 'active')->get(),
            default => collect(),
        };
    }
}
