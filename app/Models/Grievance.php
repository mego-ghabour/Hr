<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grievance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'employee_id',
        'is_anonymous',
        'type',
        'priority',
        'title',
        'content',
        'department_id',
        'against_manager',
        'attachment_path',
        'status',
        'hr_response',
        'resolved_at',
        'assigned_to',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'against_manager' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($grievance) {
            if (empty($grievance->ticket_number)) {
                $yearMonth = date('ym');
                $count = static::withTrashed()->where('ticket_number', 'like', "GRV-{$yearMonth}-%")->count() + 1;
                $grievance->ticket_number = 'GRV-' . $yearMonth . '-' . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getDisplaySubmitterNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'سري (مجهول الهوية) 🔒';
        }

        if ($this->employee) {
            return $this->employee->full_name;
        }

        return $this->user?->name ?? 'غير محدد';
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'administrative' => 'إدارية / مالية',
            'workplace' => 'بيئة العمل والأدوات',
            'behavioral' => 'سلوكية / نزاع مهني',
            'suggestion' => 'فكرة أو مقترح تطويري',
            default => 'أخرى',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'جديدة',
            'in_progress' => 'قيد المراجعة والتحقيق',
            'resolved' => 'تم الحل والمعالجة',
            'rejected' => 'مرفوضة / مغلقة',
            default => $this->status ?? 'جديدة',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'منخفضة',
            'normal' => 'عادية',
            'high' => 'عالية',
            'urgent' => 'عاجلة جداً',
            default => 'عادية',
        };
    }
}
