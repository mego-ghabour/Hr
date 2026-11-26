<?php

namespace App\Models;

use App\Enums\InterviewAttendance;
use App\Enums\InterviewStage;
use App\Enums\TalentDecisionOutcome;
use App\Enums\TalentDecisionReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class TalentReview extends Model
{
    protected $fillable = [
        'talent_id',
        'reviewer_id',
        'reviewed_at',
        'interview_stage',
        'attendance_status',
        'rating',
        'recommendation',
        'decision_reason',
        'strengths',
        'weaknesses',
        'interview_salary_expectation',
        'notice_period',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'rating' => 'integer',
            'interview_stage' => InterviewStage::class,
            'attendance_status' => InterviewAttendance::class,
            'recommendation' => TalentDecisionOutcome::class,
            'decision_reason' => TalentDecisionReason::class,
            'interview_salary_expectation' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TalentReview $review): void {
            if (empty($review->reviewer_id) && Auth::check()) {
                $review->reviewer_id = Auth::id();
            }

            if (empty($review->reviewed_at)) {
                $review->reviewed_at = now();
            }
        });
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
