<?php

namespace App\Observers;

use App\Models\Payroll;
use App\Notifications\PayrollIssuedNotification;

class PayrollObserver
{
    public function created(Payroll $payroll): void
    {
        if ($payroll->status === 'paid') {
            $this->notifyEmployee($payroll);
        }
    }

    public function updated(Payroll $payroll): void
    {
        if ($payroll->isDirty('status') && $payroll->status === 'paid') {
            $this->notifyEmployee($payroll);
        }
    }

    private function notifyEmployee(Payroll $payroll): void
    {
        try {
            $user = $payroll->employee?->user;
            if ($user) {
                $user->notify(new PayrollIssuedNotification($payroll));
            }
        } catch (\Throwable) {
            // صامت في حالة عدم اكتمال إعدادات البريد
        }
    }
}
