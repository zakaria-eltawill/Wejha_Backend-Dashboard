<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notification as NotificationModel;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_scheduled_notifications_command_processes_notifications(): void
    {
        // 1. Create a user
        $user = User::factory()->create([
            'status' => 'active',
            'preferred_language' => 'ar',
        ]);

        // 2. Create a scheduled notification for this user
        $notification = NotificationModel::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'title_ar' => 'عنوان عربي مجدول',
            'title_en' => 'Scheduled English Title',
            'content_ar' => 'محتوى عربي مجدول',
            'content_en' => 'Scheduled English Content',
            'recipient_type' => 'all',
            'status' => 'scheduled',
            'scheduled_at' => now()->subMinute(), // scheduled in the past
        ]);

        // 3. Confirm user has 0 database notifications
        $this->assertCount(0, $user->notifications);

        // 4. Run the scheduled notifications command
        $this->artisan('app:send-scheduled-notifications')
            ->expectsOutput('Found 1 scheduled notifications. Processing...')
            ->assertExitCode(0);

        // 5. Assert the notification status is now 'sent'
        $notification->refresh();
        $this->assertEquals('sent', $notification->status);

        // 6. Assert user now has 1 database notification
        $user->load('notifications');
        $this->assertCount(1, $user->notifications);

        $dbNotification = $user->notifications()->first();
        $this->assertEquals('عنوان عربي مجدول', $dbNotification->data['title']);
        $this->assertEquals('محتوى عربي مجدول', $dbNotification->data['body']);
    }
}
