<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum InterviewAttendance: string implements HasLabel, HasColor, HasIcon
{
    case ATTENDED = 'attended';
    case RESCHEDULED = 'rescheduled';
    case NO_SHOW = 'no_show';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ATTENDED => 'حضر المقابلة',
            self::RESCHEDULED => 'اعتذر / أُعيدت الجدولة',
            self::NO_SHOW => 'لم يحضر (No-Show)',
            self::CANCELLED => 'أُلغيت المقابلة',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ATTENDED => 'success',
            self::RESCHEDULED => 'warning',
            self::NO_SHOW => 'danger',
            self::CANCELLED => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ATTENDED => 'heroicon-m-check-circle',
            self::RESCHEDULED => 'heroicon-m-arrow-path',
            self::NO_SHOW => 'heroicon-m-x-circle',
            self::CANCELLED => 'heroicon-m-no-symbol',
        };
    }
}
