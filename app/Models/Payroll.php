<?php

namespace App\Models;

use App\Observers\PayrollObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

#[ObservedBy(PayrollObserver::class)]
class Payroll extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'status',
        'payment_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
        'month' => 'integer',
        'year' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getMonthNameAttribute(): string
    {
        $months = [
            1 => 'يناير',
            2 => 'فبراير',
            3 => 'مارس',
            4 => 'أبريل',
            5 => 'مايو',
            6 => 'يونيو',
            7 => 'يوليو',
            8 => 'أغسطس',
            9 => 'سبتمبر',
            10 => 'أكتوبر',
            11 => 'نوفمبر',
            12 => 'ديسمبر',
        ];

        return $months[$this->month] ?? (string)$this->month;
    }

    public function getPeriodAttribute(): string
    {
        return "{$this->month_name} {$this->year}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    protected static function booted(): void
    {
        static::saving(function (Payroll $payroll) {
            $payroll->net_salary = ($payroll->basic_salary ?? 0) + ($payroll->allowances ?? 0) - ($payroll->deductions ?? 0);
        });
    }
}
