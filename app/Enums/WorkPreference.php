<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorkPreference: string implements HasLabel
{
    case REMOTE = 'remote';
    case ONSITE = 'onsite';
    case HYBRID = 'hybrid';
    case FLEXIBLE = 'flexible';

    public function getLabel(): ?string
    {
        return match($this) {
            self::REMOTE => 'Remote',
            self::ONSITE => 'On-site',
            self::HYBRID => 'Hybrid',
            self::FLEXIBLE => 'Flexible',
        };
    }
}
