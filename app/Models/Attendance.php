<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in_time',
        'check_in_lat',
        'check_in_lng',
        'check_out_time',
        'check_out_lat',
        'check_out_lng',
        'distance_meters',
        'is_remote',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in_lat' => 'decimal:7',
        'check_in_lng' => 'decimal:7',
        'check_out_lat' => 'decimal:7',
        'check_out_lng' => 'decimal:7',
        'distance_meters' => 'integer',
        'is_remote' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTotalHoursAttribute(): ?string
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        try {
            $in = \Carbon\Carbon::parse($this->check_in_time);
            $out = \Carbon\Carbon::parse($this->check_out_time);
            $diffMinutes = $in->diffInMinutes($out);
            $hours = intdiv($diffMinutes, 60);
            $minutes = $diffMinutes % 60;

            if ($hours > 0 && $minutes > 0) {
                return "{$hours} س و {$minutes} د";
            } elseif ($hours > 0) {
                return "{$hours} ساعة";
            }
            return "{$minutes} دقيقة";
        } catch (\Throwable) {
            return null;
        }
    }
}
