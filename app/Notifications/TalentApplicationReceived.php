<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TalentApplicationReceived extends Notification
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
        $brand = \App\Models\Setting::get('brand_name', config('app.name', 'نظام إدارة الموارد البشرية'));
        $name = $this->talent->full_name;

        $defaultSubject = 'استلام طلب التوظيف بنجاح - {brand}';
        $defaultBody = "أهلاً {name}،\n\nنود إعلامك بأننا قد استلمنا طلب التوظيف الخاص بك بنجاح، وقمنا بإضافة سيرتك الذاتية إلى قاعدة بياناتنا.\n\nسيقوم فريق الموارد البشرية بمراجعة ملفك والتواصل معك في حال توافقت مهاراتك مع متطلبات الوظيفة.\n\nنتمنى لك التوفيق!\nفريق التوظيف";

        $rawSubject = \App\Models\Setting::get('email_template_application_received_subject', $defaultSubject);
        $rawBody = \App\Models\Setting::get('email_template_application_received_body', $defaultBody);

        $subject = str_replace(['{name}', '{brand}'], [$name, $brand], $rawSubject);
        $body = str_replace(['{name}', '{brand}'], [$name, $brand], $rawBody);

        $mail = (new MailMessage)->subject($subject);
        $lines = explode("\n", $body);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '') {
                $mail->line($line);
            }
        }
        return $mail;
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
