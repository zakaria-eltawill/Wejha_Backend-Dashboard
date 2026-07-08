<?php

namespace App\Console\Commands;

use App\Models\Notification as NotificationModel;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-scheduled-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and process scheduled system notifications';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $timezone = config('app.timezone', 'Africa/Tripoli');
        $now = now($timezone);

        $notifications = NotificationModel::where('status', 'scheduled')
            ->where('scheduled_at', '<=', $now)
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('No scheduled notifications to send.');
            return 0;
        }

        $this->info('Found ' . $notifications->count() . ' scheduled notifications. Processing...');

        foreach ($notifications as $notification) {
            $this->info('Sending: ' . $notification->title);
            $notificationService->sendNotification($notification);
        }

        $this->info('Done!');
        return 0;
    }
}
