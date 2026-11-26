<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TalentNoteCategory: string implements HasLabel, HasColor
{
    case GENERAL = 'general';
    case INTERVIEW = 'interview';
    case SCREENING = 'screening';
    case REJECTION = 'rejection';
    case COMMUNICATION = 'communication';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::GENERAL => 'ملاحظة عامة',
            self::INTERVIEW => 'ملاحظة مقابلة',
            self::SCREENING => 'فرز أولي',
            self::REJECTION => 'توضيح رفض / عدم ملاءمة',
            self::COMMUNICATION => 'سجل تواصل',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::GENERAL => 'gray',
            self::INTERVIEW => 'info',
            self::SCREENING => 'warning',
            self::REJECTION => 'danger',
            self::COMMUNICATION => 'success',
        };
    }
}
