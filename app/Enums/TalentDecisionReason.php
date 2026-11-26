<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TalentDecisionReason: string implements HasLabel
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
}
