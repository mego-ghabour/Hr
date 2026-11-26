<?php

namespace App\Models;

use App\Enums\TalentNoteCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class TalentNote extends Model
{
    protected $fillable = ['talent_id', 'user_id', 'category', 'body'];

    protected function casts(): array
    {
        return [
            'category' => TalentNoteCategory::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TalentNote $note): void {
            if (empty($note->user_id) && Auth::check()) {
                $note->user_id = Auth::id();
            }

            if (empty($note->category)) {
                $note->category = TalentNoteCategory::GENERAL;
            }
        });
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
