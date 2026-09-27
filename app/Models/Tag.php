<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    public function talents(): BelongsToMany
    {
        return $this->belongsToMany(Talent::class, 'talent_tags');
    }
}
