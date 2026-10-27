<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'evaluation_form_id',
        'evaluator_id',
        'evaluated_employee_id',
        'is_anonymous',
        'period',
        'answers',
        'score',
        'strengths',
        'areas_for_improvement',
        'action_plan',
        'notes',
        'status',
    ];

    protected $casts = [
        'answers' => 'array',
        'is_anonymous' => 'boolean',
        'score' => 'decimal:2',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(EvaluationForm::class, 'evaluation_form_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluatedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluated_employee_id');
    }

    public function getDisplayEvaluatorNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'سري (تم إخفاء الهوية) 🔒';
        }

        return $this->evaluator?->name ?? 'غير محدد';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'submitted' => 'تم التقديم',
            'reviewed' => 'تمت المراجعة والاعتماد',
            default => $this->status ?? 'تم التقديم',
        };
    }
}
