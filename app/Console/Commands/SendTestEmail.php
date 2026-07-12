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

        // Render the blade view manually and send as HTML to bypass ShouldQueue serialization
        $html = view('emails.student_welcome', ['user' => $user])->render();

        Mail::html($html, function ($message) use ($email) {
            $message->to($email)
                ->subject('مرحباً بك في منصة وجهة!');
        });

        $this->info('Email sent successfully! Check your inbox.');
    }
}
