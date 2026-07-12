<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'app:send-test-email {email}';
    protected $description = 'Send a test Welcome email to a given address';

    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = new User();
            $user->name = 'زكريا التويل';
            $user->email = $email;
        }

        $this->info("Sending Welcome Email to: {$email}...");

        // sendNow() bypasses the queue (WelcomeEmail implements ShouldQueue) while still
        // rendering through the real Mailable pipeline, so $message->embed() works for inline images.
        Mail::to($email)->sendNow(new WelcomeEmail($user));

        $this->info('Email sent successfully! Check your inbox.');
    }
}
