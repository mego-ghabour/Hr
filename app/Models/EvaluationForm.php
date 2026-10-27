<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'target_type',
        'department_id',
        'target_role',
        'description',
        'questions',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'questions' => 'array',
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'manager_to_employee' => 'تقييم المدير للموظف (Agent)',
            'employee_to_manager' => 'تقييم الموظف للمدير (Upward Feedback)',
            'general_survey' => 'استبيان / تقييم عام',
            default => $this->type ?? 'عام',
        };
    }

    public function getTargetLabelAttribute(): string
    {
        return match ($this->target_type) {
            'all' => 'كافة الموظفين (نموذج موحد)',
            'department' => 'قسم: ' . ($this->department?->name ?? 'غير محدد'),
            'specific_role' => 'دور محدد: ' . ($this->target_role ?? '-'),
            default => 'عام',
        };
    }

    /**
     * احتساب متوسط الدرجة من 5 أو النسبة المئوية استناداً إلى الأسئلة من نوع تقييم
     */
    public function calculateScore(array $answers): ?float
    {
        if (empty($this->questions) || !is_array($this->questions)) {
            return null;
        }

        $totalPoints = 0;
        $totalMax = 0;

        foreach ($this->questions as $index => $q) {
            $qId = $q['id'] ?? ('q_' . $index);
            $type = $q['type'] ?? 'rating_5';
            $val = $answers[$qId] ?? null;

            if ($val !== null && is_numeric($val)) {
                $maxForQuestion = ($type === 'rating_10') ? 10 : 5;
                $totalPoints += (float)$val;
                $totalMax += $maxForQuestion;
            }
        }

        if ($totalMax === 0) {
            return null;
        }

        // إرجاع الدرجة مقاسة من 5
        return round(($totalPoints / $totalMax) * 5, 2);
    }
}
