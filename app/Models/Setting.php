<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static array $inMemoryCache = [];

    /**
     * جيب قيمة إعداد معين
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, static::$inMemoryCache)) {
            return static::$inMemoryCache[$key];
        }

        $value = Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting?->value ?? $default;
        });

        return static::$inMemoryCache[$key] = $value;
    }

    /**
     * احفظ أو حدّث قيمة إعداد معين
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
        unset(static::$inMemoryCache[$key]);
    }

    public static function clearMemoryCache(): void
    {
        static::$inMemoryCache = [];
    }

    public static function currency(): string
    {
        return static::get('currency', 'EGP');
    }

    public static function currencySymbol(): string
    {
        return static::get('currency_symbol', 'ج.م');
    }

    public static function timezone(): string
    {
        return static::get('timezone', 'Africa/Cairo');
    }

    public static function dateFormat(): string
    {
        return static::get('date_format', 'Y-m-d');
    }

    public static function workStartTime(): string
    {
        return static::get('work_start_time', '09:00');
    }

    public static function workEndTime(): string
    {
        return static::get('work_end_time', '17:00');
    }

    public static function gracePeriod(): int
    {
        return (int) static::get('grace_period_minutes', 15);
    }
}
