<?php

namespace App\Models;

use App\Enums\TalentDecisionOutcome;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentStatus extends Model
{
    protected $fillable = ['name', 'color', 'is_default', 'order_column'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'order_column' => 'integer',
        ];
    }

    public function talents(): HasMany
    {
        return $this->hasMany(Talent::class, 'status_id');
    }

    public function isNegativeOrHoldStatus(): bool
    {
        $normalized = mb_strtolower(trim((string) $this->name));

        foreach (['مرفوض', 'رفض', 'معلّق', 'معلق', 'غير مناسب', 'احتياطي', 'reject', 'hold', 'not suitable'] as $keyword) {
            if (str_contains($normalized, $keyword)) {
                return true;
            }
        }

        return false;
    }

    public function suggestDecisionOutcome(): TalentDecisionOutcome
    {
        $normalized = mb_strtolower(trim((string) $this->name));

        if (str_contains($normalized, 'مرفوض') || str_contains($normalized, 'رفض') || str_contains($normalized, 'reject')) {
            return TalentDecisionOutcome::REJECTED;
        }

        if (str_contains($normalized, 'احتياطي')) {
            return TalentDecisionOutcome::RUNNER_UP;
        }

        if ($this->isNegativeOrHoldStatus()) {
            return TalentDecisionOutcome::NOT_SUITABLE;
        }

        return TalentDecisionOutcome::RECOMMENDED;
    }

    public static function findStatusForOutcome(TalentDecisionOutcome $outcome): ?self
    {
        $patterns = match ($outcome) {
            TalentDecisionOutcome::REJECTED,
            TalentDecisionOutcome::NO_SHOW => ['%مرفوض%', '%رفض%', '%reject%'],
            TalentDecisionOutcome::NOT_SUITABLE,
            TalentDecisionOutcome::RUNNER_UP,
            TalentDecisionOutcome::WITHDRAWN => ['%معلّق%', '%معلق%', '%غير مناسب%', '%احتياطي%', '%hold%', '%مرفوض%'],
            TalentDecisionOutcome::RECOMMENDED => ['%القائمة المختصرة%', '%عرض%', '%shortlist%'],
        };

        foreach ($patterns as $pattern) {
            $status = static::where('name', 'LIKE', $pattern)->first();
            if ($status) {
                return $status;
            }
        }

        return null;
    }
}
