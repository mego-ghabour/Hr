<?php

namespace App\Observers;

use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Notifications\LeaveRequestedNotification;
use App\Notifications\LeaveStatusUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class LeaveObserver
{
    public function created(Leave $leave): void
    {
        $this->handleApproval($leave);

        // إشعار المدير المباشر أو الإدارة بطلب الإجازة الجديد
        try {
            $managerUser = $leave->employee?->manager?->user;
            if ($leave->status === 'pending_manager' && $managerUser) {
                $managerUser->notify(new LeaveRequestedNotification($leave));
            } else {
                $availableRoles = \Spatie\Permission\Models\Role::whereIn('name', ['المدير العام', 'مسؤول النظام', 'مدير الموارد البشرية', 'Super Admin', 'admin'])->pluck('name')->toArray();
                if (!empty($availableRoles)) {
                    $admins = User::role($availableRoles)->get();
                    if ($admins->isNotEmpty()) {
                        Notification::send($admins, new LeaveRequestedNotification($leave));
                    }
                }
            }
        } catch (\Throwable) {
            // صامت في حالة عدم اكتمال إعدادات البريد
        }
    }

    public function updated(Leave $leave): void
    {
        if ($leave->isDirty('status')) {
            $this->handleApproval($leave);

            try {
                $employeeUser = $leave->employee?->user;

                // إذا تمت الموافقة المبدئية من المدير وتم تحويلها للـ HR
                if ($leave->status === 'pending_hr') {
                    $availableRoles = \Spatie\Permission\Models\Role::whereIn('name', ['المدير العام', 'مسؤول النظام', 'مدير الموارد البشرية', 'Super Admin', 'admin'])->pluck('name')->toArray();
                    if (!empty($availableRoles)) {
                        $admins = User::role($availableRoles)->get();
                        if ($admins->isNotEmpty()) {
                            Notification::send($admins, new LeaveRequestedNotification($leave));
                        }
                    }
                }

                // إشعار الموظف عند اعتماد أو رفض الإجازة
                if (in_array($leave->status, ['approved', 'rejected', 'pending_hr']) && $employeeUser) {
                    $employeeUser->notify(new LeaveStatusUpdatedNotification($leave));
                }
            } catch (\Throwable) {
                // صامت في حالة عدم اكتمال إعدادات البريد
            }
        }
    }

    private function handleApproval(Leave $leave): void
    {
        $days = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
        $year = Carbon::parse($leave->start_date)->year;

        $balance = LeaveBalance::firstOrCreate(
            [
                'employee_id' => $leave->employee_id,
                'leave_type_id' => $leave->leave_type_id,
                'year' => $year,
            ],
            [
                'total_days' => $leave->leaveType->default_days_per_year ?? 21,
                'used_days' => 0,
            ]
        );

        if ($leave->status === 'approved' && $leave->getOriginal('status') !== 'approved') {
            $balance->increment('used_days', $days);
        } elseif ($leave->status !== 'approved' && $leave->getOriginal('status') === 'approved') {
            $balance->decrement('used_days', $days);
        }
    }

    public function deleted(Leave $leave): void
    {
        if ($leave->status === 'approved') {
            $days = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            $year = Carbon::parse($leave->start_date)->year;

            $balance = LeaveBalance::where([
                'employee_id' => $leave->employee_id,
                'leave_type_id' => $leave->leave_type_id,
                'year' => $year,
            ])->first();

            if ($balance) {
                $balance->decrement('used_days', $days);
            }
        }
    }
}
