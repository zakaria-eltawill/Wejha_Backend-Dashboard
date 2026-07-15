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
        // Only consider events whose start has passed as archive candidates (cheap DB
        // pre-filter); the true end-of-event check (which accounts for end_date/end_time
        // on multi-day/multi-hour events) is done in PHP via Event::hasEnded().
        $now = Carbon::now(config('app.timezone', 'Africa/Tripoli'));

        $candidates = Event::where('status', EventStatus::PUBLISHED)
            ->where('event_date', '<=', $now->toDateString())
            ->get();

        $count = 0;

        foreach ($candidates as $event) {
            if (!$event->hasEnded()) {
                continue;
            }

            $event->update([
                'status' => EventStatus::ARCHIVED,
            ]);
            $this->info("Archived event: {$event->title_ar} (ID: {$event->id})");
            $count++;
        }

        $this->info("Successfully archived {$count} past events.");

        return Command::SUCCESS;
    }
}
