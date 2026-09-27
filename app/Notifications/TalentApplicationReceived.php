<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TalentApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public $talent;

    /**
     * Create a new notification instance.
     */
    public function __construct($talent)
    {
        $this->talent = $talent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('استلام طلب التوظيف بنجاح - ' . \App\Models\Setting::get('brand_name', 'نظام إدارة المرشحين'))
            ->greeting('أهلاً ' . $this->talent->full_name . '،')
            ->line('نود إعلامك بأننا قد استلمنا طلب التوظيف الخاص بك بنجاح، وقمنا بإضافة سيرتك الذاتية إلى قاعدة بياناتنا.')
            ->line('سيقوم فريق الموارد البشرية بمراجعة ملفك والتواصل معك في حال توافقت مهاراتك مع متطلبات الوظيفة.')
            ->line('نتمنى لك التوفيق!')
            ->salutation('فريق التوظيف');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
