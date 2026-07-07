<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use App\Enums\RegistrationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $operator;
    protected Event $event;
    protected Registration $registration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create();
        $this->operator = User::factory()->create();

        $this->event = Event::create([
            'title_ar' => 'فعالية اختبار حضور',
            'title_en' => 'Attendance Test Event',
            'type' => 'seminar',
            'event_date' => now()->format('Y-m-d'),
            'event_time' => '14:00',
            'venue' => 'المسرح الكبير',
            'capacity' => 10,
            'status' => 'published',
            'creator_id' => $this->operator->id,
        ]);

        $this->registration = Registration::create([
            'user_id' => $this->student->id,
            'event_id' => $this->event->id,
            'qr_hash' => Str::random(40),
            'status' => RegistrationStatus::APPROVED->value,
            'source' => 'web',
            'registered_at' => now(),
        ]);
    }

    public function test_operator_can_record_attendance_successfully(): void
    {
        $response = $this->actingAs($this->operator)->postJson(route('api.attendance.scan'), [
            'qr_hash' => $this->registration->qr_hash,
            'device' => 'Front Camera',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        
        $this->assertDatabaseHas('attendance', [
            'registration_id' => $this->registration->id,
            'scanner_user_id' => $this->operator->id,
            'device' => 'Front Camera',
        ]);

        $this->assertEquals(RegistrationStatus::CHECKED_IN->value, $this->registration->fresh()->status->value);
    }

    public function test_prevent_duplicate_attendance_scans(): void
    {
        // First scan
        $this->actingAs($this->operator)->postJson(route('api.attendance.scan'), [
            'qr_hash' => $this->registration->qr_hash,
        ]);

        // Duplicate scan
        $response = $this->actingAs($this->operator)->postJson(route('api.attendance.scan'), [
            'qr_hash' => $this->registration->qr_hash,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('success', false);
    }

    public function test_rejects_invalid_qr_hash(): void
    {
        $response = $this->actingAs($this->operator)->postJson(route('api.attendance.scan'), [
            'qr_hash' => 'invalid_hash_value',
        ]);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
    }
}
