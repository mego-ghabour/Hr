<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum DuplicateStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case REJECTED = 'rejected';
    case MERGED = 'merged';

    public function getLabel(): ?string
    {
        return match($this) {
            self::PENDING => 'قيد المراجعة',
            self::CONFIRMED => 'مؤكد',
            self::REJECTED => 'مرفوض',
            self::MERGED => 'مدمج',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'danger',
            self::REJECTED => 'gray',
            self::MERGED => 'success',
        };
    }
}
