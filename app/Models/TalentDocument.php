<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentDocument extends Model
{
    protected $fillable = [
        'talent_id',
        'type',
        'file_name',
        'file_path',
        'mime_type',
        'size',
    ];

    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'size' => 'integer',
        ];
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }
}
