<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentReview extends Model
{
    protected $fillable = ['talent_id', 'reviewer_id', 'reviewed_at', 'rating', 'notes'];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'rating' => 'integer',
        ];
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
