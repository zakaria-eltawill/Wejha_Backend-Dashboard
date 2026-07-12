<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class SendRawTestEmail extends Command
{
    protected $signature = 'app:send-raw-test {email}';
    protected $description = 'Send a raw test email to verify SMTP connection';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Testing SMTP connection to Zoho...");
        $this->info("MAIL_HOST: " . config('mail.mailers.smtp.host'));
        $this->info("MAIL_PORT: " . config('mail.mailers.smtp.port'));
        $this->info("MAIL_USERNAME: " . config('mail.mailers.smtp.username'));
        $this->info("MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption'));
        $this->info("MAIL_FROM: " . config('mail.from.address'));

        try {
            Mail::raw('هذا إيميل تجريبي من منصة وجهة - Test Email from Wejha Platform', function (Message $message) use ($email) {
                $message->to($email)
                    ->subject('تجربة إرسال من منصة وجهة');
            });

            $this->info("\nEmail sent successfully to: {$email}");
        } catch (\Throwable $e) {
            $this->error("\nFailed to send email!");
            $this->error("Error: " . $e->getMessage());
        }
    }
}
