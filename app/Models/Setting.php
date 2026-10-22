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

    public static function country(): string
    {
        return static::get('country', 'EG');
    }

    /**
     * إعدادات الدولة ورقم الجوال المخصص للسوق المستهدف
     */
    public static function getCountryPhoneConfig(?string $country = null): array
    {
        $country = $country ?: static::country();
        return match (strtoupper($country)) {
            'EG' => [
                'country' => 'EG',
                'name' => 'جمهورية مصر العربية',
                'dial_code' => '+20',
                'flag' => '🇪🇬',
                'placeholder' => '01012345678 / +20...',
                'hint' => 'رقم هاتف مصري يبدأ بـ 010 أو 011 أو 012 أو 015',
                'min_length' => 10,
                'max_length' => 13,
                'pattern' => '^(\+?20|0)?1[0125][0-9]{8}$',
            ],
            'SA' => [
                'country' => 'SA',
                'name' => 'المملكة العربية السعودية',
                'dial_code' => '+966',
                'flag' => '🇸🇦',
                'placeholder' => '0501234567 / +966...',
                'hint' => 'رقم جوال سعودي يبدأ بـ 05',
                'min_length' => 9,
                'max_length' => 14,
                'pattern' => '^(\+?966|0)?5[0-9]{8}$',
            ],
            'AE' => [
                'country' => 'AE',
                'name' => 'الإمارات العربية المتحدة',
                'dial_code' => '+971',
                'flag' => '🇦🇪',
                'placeholder' => '0501234567 / +971...',
                'hint' => 'رقم جوال إماراتي يبدأ بـ 05',
                'min_length' => 9,
                'max_length' => 14,
                'pattern' => '^(\+?971|0)?5[0-9]{8}$',
            ],
            'KW' => [
                'country' => 'KW',
                'name' => 'دولة الكويت',
                'dial_code' => '+965',
                'flag' => '🇰🇼',
                'placeholder' => '5xxxxxxx / 6xxxxxxx / 9xxxxxxx',
                'hint' => 'رقم هاتف كويتي (8 أرقام)',
                'min_length' => 8,
                'max_length' => 13,
                'pattern' => '^(\+?965)?[569][0-9]{7}$',
            ],
            'QA' => [
                'country' => 'QA',
                'name' => 'دولة قطر',
                'dial_code' => '+974',
                'flag' => '🇶🇦',
                'placeholder' => '3xxxxxxx / 5xxxxxxx / 6xxxxxxx',
                'hint' => 'رقم هاتف قطري (8 أرقام)',
                'min_length' => 8,
                'max_length' => 13,
                'pattern' => '^(\+?974)?[3567][0-9]{7}$',
            ],
            'OM' => [
                'country' => 'OM',
                'name' => 'سلطنة عمان',
                'dial_code' => '+968',
                'flag' => '🇴🇲',
                'placeholder' => '7xxxxxxx / 9xxxxxxx',
                'hint' => 'رقم هاتف عماني يبدأ بـ 7 أو 9',
                'min_length' => 8,
                'max_length' => 13,
                'pattern' => '^(\+?968)?[79][0-9]{7}$',
            ],
            'BH' => [
                'country' => 'BH',
                'name' => 'مملكة البحرين',
                'dial_code' => '+973',
                'flag' => '🇧🇭',
                'placeholder' => '3xxxxxxx / 6xxxxxxx',
                'hint' => 'رقم هاتف بحريني يبدأ بـ 3 أو 6',
                'min_length' => 8,
                'max_length' => 13,
                'pattern' => '^(\+?973)?[36][0-9]{7}$',
            ],
            default => [
                'country' => 'ALL',
                'name' => 'دولي (جميع الدول)',
                'dial_code' => '+',
                'flag' => '🌍',
                'placeholder' => '01xxxxxxxxx / +000...',
                'hint' => 'أدخل رقم الهاتف مع أو بدون رمز الدولة',
                'min_length' => 7,
                'max_length' => 20,
                'pattern' => '^\+?[0-9][0-9\s\-\(\)]{6,20}$',
            ],
        };
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
