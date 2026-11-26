<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TalentDecisionOutcome: string implements HasLabel, HasColor
{
    case RECOMMENDED = 'recommended';
    case RUNNER_UP = 'runner_up';
    case NOT_SUITABLE = 'not_suitable';
    case REJECTED = 'rejected';
    case NO_SHOW = 'no_show';
    case WITHDRAWN = 'withdrawn';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::RECOMMENDED => 'موصى به / اجتاز المقابلة',
            self::RUNNER_UP => 'احتياطي / مناسب لوظيفة أخرى',
            self::NOT_SUITABLE => 'غير مناسب حالياً (ليس خياراً مناسباً)',
            self::REJECTED => 'مرفوض (غير مقبول)',
            self::NO_SHOW => 'لم يحضر المقابلة (No-Show)',
            self::WITHDRAWN => 'انسحب المرشح',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::RECOMMENDED => 'success',
            self::RUNNER_UP => 'info',
            self::NOT_SUITABLE => 'warning',
            self::REJECTED => 'danger',
            self::NO_SHOW => 'danger',
            self::WITHDRAWN => 'gray',
        };
    }

    public function isNegativeOrHold(): bool
    {
        return $this !== self::RECOMMENDED;
    }

    public function requiresReason(): bool
    {
        return in_array($this, [
            self::NOT_SUITABLE,
            self::REJECTED,
            self::NO_SHOW,
            self::WITHDRAWN,
        ], true);
    }
}
