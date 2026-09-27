<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocumentType: string implements HasLabel
{
    case CV = 'cv';
    case COVER_LETTER = 'cover_letter';
    case CERTIFICATE = 'certificate';
    case PORTFOLIO = 'portfolio';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match($this) {
            self::CV => 'CV',
            self::COVER_LETTER => 'Cover Letter',
            self::CERTIFICATE => 'Certificate',
            self::PORTFOLIO => 'Portfolio',
            self::OTHER => 'Other',
        };
    }
}
