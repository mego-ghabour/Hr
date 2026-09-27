<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SeniorityLevel: string implements HasLabel
{
    case INTERN = 'intern';
    case JUNIOR = 'junior';
    case MID = 'mid';
    case SENIOR = 'senior';
    case LEAD = 'lead';
    case MANAGER = 'manager';
    case DIRECTOR = 'director';
    case VP = 'vp';
    case C_LEVEL = 'c_level';

    public function getLabel(): ?string
    {
        return match($this) {
            self::INTERN => 'Intern',
            self::JUNIOR => 'Junior',
            self::MID => 'Mid',
            self::SENIOR => 'Senior',
            self::LEAD => 'Lead',
            self::MANAGER => 'Manager',
            self::DIRECTOR => 'Director',
            self::VP => 'VP',
            self::C_LEVEL => 'C-Level',
        };
    }
}
