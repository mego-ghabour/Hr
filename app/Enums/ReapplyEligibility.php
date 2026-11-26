<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ReapplyEligibility: string implements HasLabel, HasColor, HasIcon
{
    case ELIGIBLE_NOW = 'eligible_now';
    case AFTER_6_MONTHS = 'after_6_months';
    case AFTER_12_MONTHS = 'after_12_months';
    case DO_NOT_REHIRE = 'do_not_rehire';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ELIGIBLE_NOW => 'مناسب لوظيفة أخرى حالياً',
            self::AFTER_6_MONTHS => 'مؤهل لإعادة التقديم بعد 6 أشهر',
            self::AFTER_12_MONTHS => 'مؤهل لإعادة التقديم بعد سنة',
            self::DO_NOT_REHIRE => 'لا يُعاد ترشيحه مستقبلاً (Do Not Rehire)',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ELIGIBLE_NOW => 'success',
            self::AFTER_6_MONTHS => 'info',
            self::AFTER_12_MONTHS => 'warning',
            self::DO_NOT_REHIRE => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ELIGIBLE_NOW => 'heroicon-m-check-badge',
            self::AFTER_6_MONTHS => 'heroicon-m-clock',
            self::AFTER_12_MONTHS => 'heroicon-m-calendar-days',
            self::DO_NOT_REHIRE => 'heroicon-m-no-symbol',
        };
    }
}
