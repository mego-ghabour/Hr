<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TalentDecisionReason: string implements HasLabel, HasIcon
{
    case TECHNICAL_GAP = 'technical_gap';
    case SALARY_MISMATCH = 'salary_mismatch';
    case OVERQUALIFIED = 'overqualified';
    case UNDERQUALIFIED = 'underqualified';
    case COMMUNICATION_CULTURE = 'communication_culture';
    case SCHEDULE_LOCATION = 'schedule_location';
    case BETTER_CANDIDATE = 'better_candidate';
    case NO_SHOW = 'no_show';
    case CANDIDATE_WITHDREW = 'candidate_withdrew';
    case RED_FLAG = 'red_flag';
    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TECHNICAL_GAP => 'فجوة في المهارات الفنية / الخبرة',
            self::SALARY_MISMATCH => 'عدم توافق الراتب مع الميزانية',
            self::OVERQUALIFIED => 'مستوى الخبرة أعلى من المطلوب (Overqualified)',
            self::UNDERQUALIFIED => 'مستوى الخبرة أقل من المطلوب',
            self::COMMUNICATION_CULTURE => 'مهارات التواصل أو الملاءمة الثقافية',
            self::SCHEDULE_LOCATION => 'عدم توافق الموقع أو نظام/مواعيد العمل',
            self::BETTER_CANDIDATE => 'تم اختيار مرشح آخر أكثر ملاءمة',
            self::NO_SHOW => 'لم يحضر المقابلة بدون اعتذار / متابعة',
            self::CANDIDATE_WITHDREW => 'انسحاب المرشح أو رفض العرض',
            self::RED_FLAG => 'تحفظ إداري / عدم مصداقية / سلوك غير مناسب',
            self::OTHER => 'سبب آخر (موضح في الملخص)',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TECHNICAL_GAP => 'heroicon-m-wrench-screwdriver',
            self::SALARY_MISMATCH => 'heroicon-m-banknotes',
            self::OVERQUALIFIED => 'heroicon-m-arrow-trending-up',
            self::UNDERQUALIFIED => 'heroicon-m-arrow-trending-down',
            self::COMMUNICATION_CULTURE => 'heroicon-m-chat-bubble-left-right',
            self::SCHEDULE_LOCATION => 'heroicon-m-map-pin',
            self::BETTER_CANDIDATE => 'heroicon-m-users',
            self::NO_SHOW => 'heroicon-m-user-minus',
            self::CANDIDATE_WITHDREW => 'heroicon-m-arrow-right-start-on-rectangle',
            self::RED_FLAG => 'heroicon-m-flag',
            self::OTHER => 'heroicon-m-ellipsis-horizontal-circle',
        };
    }
}
