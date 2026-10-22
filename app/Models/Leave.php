<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\LeaveObserver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[ObservedBy(LeaveObserver::class)]
class Leave extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'admin_notes',
        'manager_approved_at',
        'manager_approved_by',
        'manager_notes',
        'hr_approved_at',
        'hr_approved_by',
    ];

    protected $casts = [
        'manager_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function managerApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_approved_by');
    }

    public function hrApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_approved_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_manager' => 'بانتظار موافقة المدير المباشر',
            'pending_hr' => 'بانتظار موافقة الموارد البشرية',
            'approved' => 'معتمد نهائياً',
            'rejected' => 'مرفوض',
            default => 'قيد الانتظار',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending_manager' => 'warning',
            'pending_hr' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }
}
