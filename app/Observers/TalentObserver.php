<?php

namespace App\Observers;

use App\Models\Talent;
use App\Services\TalentNormalizationService;
use App\Actions\Talent\DetectDuplicateTalentAction;
use Illuminate\Support\Facades\Auth;

class TalentObserver
{
    protected TalentNormalizationService $normalizationService;
    protected DetectDuplicateTalentAction $duplicateAction;

    public function __construct(
        TalentNormalizationService $normalizationService,
        DetectDuplicateTalentAction $duplicateAction
    ) {
        $this->normalizationService = $normalizationService;
        $this->duplicateAction = $duplicateAction;
    }

    public function creating(Talent $talent): void
    {
        $normalizedData = $this->normalizationService->normalize($talent->toArray());
        $talent->fill($normalizedData);

        if (empty($talent->created_by) && Auth::check()) {
            $talent->created_by = Auth::id();
        }
    }
    
    public function created(Talent $talent): void
    {
        $this->duplicateAction->execute($talent);
    }
    
    public function updating(Talent $talent): void
    {
        $normalizedData = $this->normalizationService->normalize($talent->toArray());
        $talent->fill($normalizedData);
    }
}
