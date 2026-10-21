<?php

namespace App\Notifications;

use App\Models\Payroll;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollIssuedNotification extends Notification
{
    use Queueable;

    public function __construct(public Payroll $payroll)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $brandName = Setting::get('brand_name', 'نظام الموارد البشرية');
        $currency = Setting::get('currency', 'SAR');
        $employeeName = $this->payroll->employee?->full_name ?? 'عزيزي الموظف';
        $netSalary = number_format($this->payroll->net_salary, 2);

        return (new MailMessage)
            ->subject("إشعار صرف مسير الراتب - شهر {$this->payroll->month_name} {$this->payroll->year}")
            ->greeting("أهلاً بك، {$employeeName}")
            ->line("يسرنا إبلاغك بأنه تم إيداع واعتماد مسير راتبك لشهر {$this->payroll->month_name} {$this->payroll->year}.")
            ->line("الراتب الأساسي: " . number_format($this->payroll->basic_salary, 2) . " {$currency}")
            ->line("البدلات والحوافز: +" . number_format($this->payroll->allowances ?? 0, 2) . " {$currency}")
            ->line("الاستقطاعات والخصومات: -" . number_format($this->payroll->deductions ?? 0, 2) . " {$currency}")
            ->line("صافي الراتب المستحق: {$netSalary} {$currency}")
            ->action('استعراض وتحميل قسيمة الراتب (Payslip)', url('/portal'))
            ->line("نشكرك على جهودك وتفانيك في العمل - إدارة الشؤون المالية والموارد البشرية في {$brandName}.");
    }

    public function toDatabase(object $notifiable): array
    {
        $currency = Setting::get('currency', 'SAR');
        $netSalary = number_format($this->payroll->net_salary, 2);

        return [
            'title' => "تم صرف مسير راتب شهر {$this->payroll->month_name} {$this->payroll->year}",
            'body' => "صافي الراتب المستحق: {$netSalary} {$currency}",
            'url' => '/portal',
            'payroll_id' => $this->payroll->id,
        ];
    }
}
