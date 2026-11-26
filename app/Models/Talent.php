<?php

namespace App\Models;

use App\Enums\Availability;
use App\Enums\ReapplyEligibility;
use App\Enums\SeniorityLevel;
use App\Enums\TalentDecisionOutcome;
use App\Enums\TalentDecisionReason;
use App\Enums\WorkPreference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class Talent extends Model
{
    protected $table = 'talents';

    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'talent_id',
        'full_name',
        'email',
        'phone',
        'linkedin_url',
        'portfolio_url',
        'current_job_title',
        'current_company',
        'years_of_experience',
        'expected_salary',
        'seniority_level',
        'work_preference',
        'availability',
        'availability_option_id',
        'department_id',
        'location_id',
        'source_id',
        'status_id',
        'job_posting_id',
        'created_by',
        'assigned_recruiter_id',
        'date_added',
        'last_profile_review_at',
        'general_notes',
        'decision_outcome',
        'decision_reason',
        'decision_summary',
        'reapply_eligibility',
        'interview_salary_expectation',
        'recommended_job_posting_id',
        'decision_by',
        'decision_at',
        'custom_answers',
        'is_potential_duplicate',
    ];

    protected function casts(): array
    {
        return [
            'years_of_experience' => 'decimal:1',
            'expected_salary' => 'decimal:2',
            'interview_salary_expectation' => 'decimal:2',
            'seniority_level' => SeniorityLevel::class,
            'work_preference' => WorkPreference::class,
            'availability' => Availability::class,
            'decision_outcome' => TalentDecisionOutcome::class,
            'decision_reason' => TalentDecisionReason::class,
            'reapply_eligibility' => ReapplyEligibility::class,
            'date_added' => 'datetime',
            'last_profile_review_at' => 'datetime',
            'decision_at' => 'datetime',
            'is_potential_duplicate' => 'boolean',
            'custom_answers' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->talent_id)) {
                // Assign a temporary unique ID to satisfy the non-null database constraint
                $model->talent_id = 'TL-TMP-' . uniqid() . '-' . rand(1000, 9999);
            }
        });

        static::created(function ($model) {
            // Now that the record is inserted and we have the real auto-increment ID, set the final sequential ID
            $model->talent_id = 'TL-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->saveQuietly();
        });
    }

    public function getFirstNameAttribute(): string
    {
        $parts = explode(' ', trim($this->full_name ?? ''), 2);
        return $parts[0] ?? '';
    }

    public function getLastNameAttribute(): string
    {
        $parts = explode(' ', trim($this->full_name ?? ''), 2);
        return $parts[1] ?? '';
    }

    public function getProfileAgeAttribute(): int
    {
        if (!$this->date_added) {
            return 0;
        }
        return Carbon::parse($this->date_added)->diffInDays(now());
    }

    public function scopeActive($query)
    {
        return $query->whereHas('status', function($q) {
            $q->where('name', '!=', 'Archived');
        });
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function recommendedJobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'recommended_job_posting_id');
    }

    public function decisionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decision_by');
    }

    public function hasCaseDecision(): bool
    {
        return $this->decision_outcome !== null
            || $this->decision_reason !== null
            || filled($this->decision_summary);
    }

    /**
     * Find an earlier talent record with the same email, phone, or linked duplicate that has a recorded case decision.
     */
    public function findPreviousCaseRecord(): ?self
    {
        $duplicateIds = Duplicate::query()
            ->where(function ($query) {
                $query->where('original_talent_id', $this->id)
                    ->orWhere('duplicate_talent_id', $this->id);
            })
            ->get()
            ->flatMap(fn (Duplicate $d) => [$d->original_talent_id, $d->duplicate_talent_id])
            ->reject(fn ($id) => (int) $id === (int) $this->id)
            ->unique()
            ->values()
            ->all();

        if (empty($this->email) && empty($this->phone) && empty($duplicateIds)) {
            return null;
        }

        return static::query()
            ->where('id', '!=', $this->id)
            ->where(function ($query) use ($duplicateIds) {
                if (! empty($this->email)) {
                    $query->orWhere('email', $this->email);
                }
                if (! empty($this->phone)) {
                    $query->orWhere('phone', $this->phone);
                }
                if (! empty($duplicateIds)) {
                    $query->orWhereIn('id', $duplicateIds);
                }
            })
            ->where(function ($query) {
                $query->whereNotNull('decision_outcome')
                    ->orWhereNotNull('decision_reason')
                    ->orWhereNotNull('decision_summary');
            })
            ->with(['jobPosting', 'decisionBy'])
            ->latest('decision_at')
            ->latest('id')
            ->first();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function availabilityOption(): BelongsTo
    {
        return $this->belongsTo(AvailabilityOption::class, 'availability_option_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TalentStatus::class, 'status_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedRecruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_recruiter_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'talent_tags');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(TalentNote::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(TalentReview::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(TalentFollowUp::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TalentDocument::class);
    }

    public function duplicatesOriginal(): HasMany
    {
        return $this->hasMany(Duplicate::class, 'original_talent_id');
    }

    public function duplicatesDuplicate(): HasMany
    {
        return $this->hasMany(Duplicate::class, 'duplicate_talent_id');
    }
}
