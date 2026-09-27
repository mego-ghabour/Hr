<?php

namespace App\Models;

use App\Enums\DuplicateStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Duplicate extends Model
{
    protected $fillable = [
        'original_talent_id',
        'duplicate_talent_id',
        'matching_fields',
        'confidence',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'matching_fields' => 'array',
            'confidence' => 'integer',
            'status' => DuplicateStatus::class,
        ];
    }

    public function originalTalent(): BelongsTo
    {
        return $this->belongsTo(Talent::class, 'original_talent_id');
    }

    public function duplicateTalent(): BelongsTo
    {
        return $this->belongsTo(Talent::class, 'duplicate_talent_id');
    }
}
