<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Registration $registration
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأكيد التسجيل وتذكرة الدخول - ' . ($this->registration->event->title_ar ?? ''),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event_registration',
            with: [
                'registration' => $this->registration,
                'event' => $this->registration->event,
                'user' => $this->registration->user,
            ]
        );
    }
}
