<?php

namespace App\Models;

use App\Enums\FollowUpPriority;
use App\Enums\FollowUpStatus;
use App\Enums\FollowUpType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentFollowUp extends Model
{
    protected $table = 'talent_follow_ups';

    protected $fillable = [
        'talent_id',
        'assigned_to',
        'follow_up_date',
        'type',
        'priority',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'datetime',
            'type' => FollowUpType::class,
            'priority' => FollowUpPriority::class,
            'status' => FollowUpStatus::class,
        ];
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
