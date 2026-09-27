<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentStatus extends Model
{
    protected $fillable = ['name', 'color', 'is_default', 'order_column'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'order_column' => 'integer',
        ];
    }

    public function talents(): HasMany
    {
        return $this->hasMany(Talent::class, 'status_id');
    }
}
