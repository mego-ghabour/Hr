<?php

namespace App\Notifications;

use App\Models\Leave;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(public Leave $leave)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $brandName = Setting::get('brand_name', 'نظام الموارد البشرية');
        $employeeName = $this->leave->employee?->full_name ?? 'موظف';
        $leaveTypeName = $this->leave->leaveType?->name ?? 'إجازة';
        $days = Carbon::parse($this->leave->start_date)->diffInDays(Carbon::parse($this->leave->end_date)) + 1;

        return (new MailMessage)
            ->subject("طلب إجازة جديد: {$employeeName} ({$leaveTypeName})")
            ->greeting("مرحباً بك في {$brandName}")
            ->line("تم تقديم طلب إجازة جديد من الموظف: {$employeeName}")
            ->line("نوع الإجازة: {$leaveTypeName}")
            ->line("الفترة: من {$this->leave->start_date} إلى {$this->leave->end_date} ({$days} أيام)")
            ->line("السبب: " . ($this->leave->reason ?? 'غير محدد'))
            ->action('مراجعة طلب الإجازة في لوحة الإدارة', url('/admin/leaves'))
            ->line('يرجى مراجعة الطلب واتخاذ القرار المناسب بالاعتماد أو الرفض.');
    }

    public function toDatabase(object $notifiable): array
    {
        $employeeName = $this->leave->employee?->full_name ?? 'موظف';
        $leaveTypeName = $this->leave->leaveType?->name ?? 'إجازة';

        return [
            'title' => "طلب إجازة جديد من {$employeeName}",
            'body' => "طلب إجازة {$leaveTypeName} من {$this->leave->start_date} إلى {$this->leave->end_date}",
            'url' => '/admin/leaves',
            'leave_id' => $this->leave->id,
        ];
    }
}
