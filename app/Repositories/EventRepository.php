<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventRepository
{
    public function find(string $id): ?Event
    {
        return Event::find($id);
    }

    public function getActiveEvents(): Collection
    {
        return Event::where('status', 'published')
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->get();
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(Event $event, array $data): bool
    {
        return $event->update($data);
    }

    public function delete(Event $event): bool
    {
        return $event->delete();
    }
}
