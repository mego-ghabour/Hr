<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InterviewStage: string implements HasLabel, HasColor
{
    case HR_SCREENING = 'hr_screening';
    case TECHNICAL = 'technical';
    case MANAGER = 'manager';
    case FINAL = 'final';
    case GENERAL_REVIEW = 'general_review';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::HR_SCREENING => 'مقابلة HR مبدئية',
            self::TECHNICAL => 'مقابلة فنية (Technical)',
            self::MANAGER => 'مقابلة مدير القسم',
            self::FINAL => 'المقابلة النهائية',
            self::GENERAL_REVIEW => 'مراجعة عامة للملف',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::HR_SCREENING => 'info',
            self::TECHNICAL => 'primary',
            self::MANAGER => 'warning',
            self::FINAL => 'success',
            self::GENERAL_REVIEW => 'gray',
        };
    }
}
