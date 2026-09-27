<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum FollowUpStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case OVERDUE = 'overdue';

    public function getLabel(): ?string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::OVERDUE => 'Overdue',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            self::CANCELLED => 'gray',
            self::OVERDUE => 'danger',
        };
    }
}
