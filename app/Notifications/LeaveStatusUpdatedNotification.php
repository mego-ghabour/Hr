<?php

namespace App\Notifications;

use App\Models\Leave;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveStatusUpdatedNotification extends Notification
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
        $employeeName = $this->leave->employee?->full_name ?? 'عزيزي الموظف';
        $leaveTypeName = $this->leave->leaveType?->name ?? 'إجازة';
        $days = Carbon::parse($this->leave->start_date)->diffInDays(Carbon::parse($this->leave->end_date)) + 1;
        $statusText = match ($this->leave->status) {
            'approved' => 'الموافقة النهائية على',
            'pending_hr' => 'موافقة مديرك المباشر على',
            'rejected' => 'رفض',
            default => 'تحديث حالة',
        };

        $mail = (new MailMessage)
            ->subject("تحديث حالة طلب الإجازة: تم {$statusText} طلبك")
            ->greeting("أهلاً بك، {$employeeName}")
            ->line("نود إفادتك بأنه تم {$statusText} طلب الإجازة المقدم من قبلك:")
            ->line("نوع الإجازة: {$leaveTypeName}")
            ->line("الفترة: من {$this->leave->start_date} إلى {$this->leave->end_date} ({$days} أيام)");

        if (!empty($this->leave->manager_notes)) {
            $mail->line("ملاحظات المدير المباشر: {$this->leave->manager_notes}");
        }

        if (!empty($this->leave->admin_notes)) {
            $mail->line("ملاحظات الإدارة: {$this->leave->admin_notes}");
        }

        return $mail
            ->action('عرض تفاصيل الإجازة ورصيدك في بوابة الموظف', url('/portal'))
            ->line("نتمنى لك دوام التوفيق والنجاح - فريق {$brandName}.");
    }

    public function toDatabase(object $notifiable): array
    {
        $statusText = match ($this->leave->status) {
            'approved' => 'الموافقة النهائية على',
            'pending_hr' => 'موافقة مديرك المباشر على',
            'rejected' => 'رفض',
            default => 'تحديث حالة',
        };
        $leaveTypeName = $this->leave->leaveType?->name ?? 'إجازة';

        return [
            'title' => "تم {$statusText} طلب الإجازة الخاص بك",
            'body' => "طلب إجازة {$leaveTypeName} من {$this->leave->start_date} إلى {$this->leave->end_date}",
            'url' => '/portal',
            'status' => $this->leave->status,
        ];
    }
}
