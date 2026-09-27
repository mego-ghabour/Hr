<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentNote extends Model
{
    protected $fillable = ['talent_id', 'user_id', 'body'];

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
