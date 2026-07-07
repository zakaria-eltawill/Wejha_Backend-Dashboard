<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'status' => 'active',
        ]);

        $this->event = Event::create([
            'title_ar' => 'فعالية تجريبية',
            'title_en' => 'Test Event',
            'type' => 'workshop',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'event_time' => '10:00',
            'venue' => 'قاعة أ',
            'capacity' => 2,
            'status' => 'published',
            'registration_opens_at' => now()->subDays(1),
            'registration_closes_at' => now()->addDays(2),
            'creator_id' => $this->user->id,
        ]);
    }

    public function test_user_can_register_for_event_successfully(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('api.events.register'), [
            'event_id' => $this->event->id,
            'source' => 'web',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        
        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
            'status' => RegistrationStatus::APPROVED->value,
        ]);
    }

    public function test_prevent_duplicate_registrations(): void
    {
        // First booking
        $this->actingAs($this->user)->postJson(route('api.events.register'), [
            'event_id' => $this->event->id,
        ]);

        // Duplicate booking
        $response = $this->actingAs($this->user)->postJson(route('api.events.register'), [
            'event_id' => $this->event->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
    }

    public function test_prevent_registration_when_capacity_exceeded(): void
    {
        // Register 2 users (capacity = 2)
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1)->postJson(route('api.events.register'), ['event_id' => $this->event->id]);
        $this->actingAs($user2)->postJson(route('api.events.register'), ['event_id' => $this->event->id]);

        // Try registering 3rd user
        $user3 = User::factory()->create();
        $response = $this->actingAs($user3)->postJson(route('api.events.register'), ['event_id' => $this->event->id]);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
    }
}
