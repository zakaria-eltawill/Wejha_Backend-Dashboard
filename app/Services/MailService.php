<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\GenericMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailService
{
    /**
     * Send an email asynchronously using the Queue.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $body
     * @param array $extraData
     * @return bool
     */
    public function sendEmail(string $recipient, string $subject, string $body, array $extraData = []): bool
    {
        try {
            Mail::to($recipient)->send(new GenericMail($subject, $body, $extraData));
            return true;
        } catch (Throwable $e) {
            Log::error('Failed to send email.', [
                'recipient' => $recipient,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
