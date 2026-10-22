<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Excuse extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'start_time',
        'end_time',
        'duration_minutes',
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

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
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
