<?php

namespace App\Console\Commands;

use App\Enums\EventStatus;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ArchivePastEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:archive-past-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically archives past events whose date and time have passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Riyadh');
        $todayDate = $now->toDateString();
        $currentTime = $now->toTimeString();

        // Get events that are published but their date is in the past,
        // OR their date is today but their time has already passed.
        $eventsToArchive = Event::where('status', EventStatus::PUBLISHED)
            ->where(function ($query) use ($todayDate, $currentTime) {
                $query->where('event_date', '<', $todayDate)
                    ->orWhere(function ($q) use ($todayDate, $currentTime) {
                        $q->where('event_date', $todayDate)
                            ->where('event_time', '<', $currentTime);
                    });
            })
            ->get();

        $count = $eventsToArchive->count();

        foreach ($eventsToArchive as $event) {
            $event->update([
                'status' => EventStatus::ARCHIVED,
            ]);
            $this->info("Archived event: {$event->title_ar} (ID: {$event->id})");
        }

        $this->info("Successfully archived {$count} past events.");

        return Command::SUCCESS;
    }
}
