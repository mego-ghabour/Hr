<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityOption extends Model
{
    protected $fillable = ['name', 'name_en', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function talents()
    {
        return $this->hasMany(Talent::class, 'availability_option_id');
    }

    public static function activeOptions(): array
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray();
    }
}
