<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Notification as NotificationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public NotificationModel $notificationModel
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $lang = $notifiable->preferred_language ?? 'ar';
        $title = $lang === 'ar' ? $this->notificationModel->title_ar : $this->notificationModel->title_en;
        $content = $lang === 'ar' ? $this->notificationModel->content_ar : $this->notificationModel->content_en;

        return (new MailMessage)
            ->subject($title)
            ->line($content)
            ->action($lang === 'ar' ? 'عرض في المنصة' : 'View on Platform', url('/'))
            ->line($lang === 'ar' ? 'شكراً لاستخدامك منصتنا!' : 'Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $lang = $notifiable->preferred_language ?? 'ar';
        $title = $lang === 'ar' ? $this->notificationModel->title_ar : $this->notificationModel->title_en;
        $content = $lang === 'ar' ? $this->notificationModel->content_ar : $this->notificationModel->content_en;

        return [
            'title' => $title,
            'body' => $content,
            'notification_id' => $this->notificationModel->id,
            'title_ar' => $this->notificationModel->title_ar,
            'title_en' => $this->notificationModel->title_en,
            'content_ar' => $this->notificationModel->content_ar,
            'content_en' => $this->notificationModel->content_en,
        ];
    }
}
