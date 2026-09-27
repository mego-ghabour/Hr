<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum FollowUpType: string implements HasLabel
{
    case CALL = 'call';
    case EMAIL = 'email';
    case MEETING = 'meeting';
    case INTERVIEW = 'interview';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match($this) {
            self::CALL => 'Call',
            self::EMAIL => 'Email',
            self::MEETING => 'Meeting',
            self::INTERVIEW => 'Interview',
            self::OTHER => 'Other',
        };
    }
}
