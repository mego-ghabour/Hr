<?php

namespace App\Models;

use App\Enums\Availability;
use App\Enums\SeniorityLevel;
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
        'custom_answers',
        'is_potential_duplicate',
    ];

    protected function casts(): array
    {
        return [
            'years_of_experience' => 'decimal:1',
            'expected_salary' => 'decimal:2',
            'seniority_level' => SeniorityLevel::class,
            'work_preference' => WorkPreference::class,
            'availability' => Availability::class,
            'date_added' => 'datetime',
            'last_profile_review_at' => 'datetime',
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
