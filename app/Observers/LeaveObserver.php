<?php

namespace App\Observers;

use App\Models\Leave;
use App\Models\LeaveBalance;
use Carbon\Carbon;

class LeaveObserver
{
    public function created(Leave $leave): void
    {
        $this->handleApproval($leave);
    }

    public function updated(Leave $leave): void
    {
        if ($leave->isDirty('status')) {
            $this->handleApproval($leave);
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

    public function restored(Leave $leave): void
    {
        //
    }

    public function forceDeleted(Leave $leave): void
    {
        //
    }
}
