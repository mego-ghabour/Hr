<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Availability: string implements HasLabel
{
    case IMMEDIATELY = 'immediately';
    case TWO_WEEKS = 'two_weeks';
    case ONE_MONTH = 'one_month';
    case TWO_MONTHS = 'two_months';
    case NOT_AVAILABLE = 'not_available';

    public function getLabel(): ?string
    {
        return match($this) {
            self::IMMEDIATELY => 'Immediately',
            self::TWO_WEEKS => '2 Weeks',
            self::ONE_MONTH => '1 Month',
            self::TWO_MONTHS => '2 Months',
            self::NOT_AVAILABLE => 'Not Available',
        };
    }
}
