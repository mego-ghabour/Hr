<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'department_id',
        'location_id',
        'is_active',
        'success_message',
        'brand_color',
        'form_layout',
        'form_schema',
        'primary_fields_config',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'form_schema' => 'array',
            'primary_fields_config' => 'array',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
