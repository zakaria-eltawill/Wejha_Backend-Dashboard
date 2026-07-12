<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Enums\RegistrationStatus;
use App\Mail\EventReminderEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEventReminders extends Command
{
    protected $signature = 'app:send-event-reminders';
    protected $description = 'Send reminder emails to students registered for events happening tomorrow.';

    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();
        $this->info("Sending reminders for events on: {$tomorrow}");

        $events = Event::whereDate('event_date', $tomorrow)
            ->with(['registrations' => function ($query) {
                $query->where('status', RegistrationStatus::APPROVED->value);
            }, 'registrations.user'])
            ->get();

        $count = 0;

        foreach ($events as $event) {
            foreach ($event->registrations as $registration) {
                if ($registration->user) {
                    Mail::to($registration->user->email)->send(new EventReminderEmail($registration));
                    $count++;
                }
            }
        }

        $this->info("Sent {$count} reminders.");
        Log::info("Sent {$count} event reminders for events on {$tomorrow}.");
    }
}
