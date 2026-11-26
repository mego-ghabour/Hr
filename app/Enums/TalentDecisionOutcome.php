<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TalentDecisionOutcome: string implements HasLabel, HasColor, HasIcon, HasDescription
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

    public function getIcon(): ?string
    {
        return match ($this) {
            self::RECOMMENDED => 'heroicon-m-check-circle',
            self::RUNNER_UP => 'heroicon-m-clock',
            self::NOT_SUITABLE => 'heroicon-m-exclamation-triangle',
            self::REJECTED => 'heroicon-m-x-circle',
            self::NO_SHOW => 'heroicon-m-user-minus',
            self::WITHDRAWN => 'heroicon-m-arrow-right-start-on-rectangle',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::RECOMMENDED => 'المرشح مناسب ويُنصح بالمضي في التوظيف',
            self::RUNNER_UP => 'مرشح جيد لكن يوجد أفضل، يمكن ترشيحه لوظيفة أخرى',
            self::NOT_SUITABLE => 'لا يتوافق مع متطلبات الوظيفة الحالية',
            self::REJECTED => 'تم رفض المرشح نهائياً لأسباب واضحة',
            self::NO_SHOW => 'المرشح لم يحضر المقابلة ولم يعتذر',
            self::WITHDRAWN => 'المرشح قرر الانسحاب من تلقاء نفسه',
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
