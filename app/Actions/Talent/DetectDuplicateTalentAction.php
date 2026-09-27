<?php

namespace App\Actions\Talent;

use App\Models\Duplicate;
use App\Models\Talent;
use Illuminate\Support\Collection;

class DetectDuplicateTalentAction
{
    public function execute(Talent $talent): Collection
    {
        $duplicates = collect();
        if (!$talent->exists) {
            return $duplicates;
        }

        $query = Talent::query()
            ->where('id', '!=', $talent->id)
            ->whereNull('deleted_at'); // assuming soft deletes

        $potentialMatches = $query->get();

        foreach ($potentialMatches as $match) {
            $confidence = 0;
            $matchingFields = [];

            // 1. Exact email match = 100%
            if ($talent->email && $match->email && $talent->email === $match->email) {
                $confidence = 100;
                $matchingFields[] = 'email';
            } 
            // 2. Exact phone match = 90%
            elseif ($talent->phone && $match->phone && $talent->phone === $match->phone) {
                $confidence = 90;
                $matchingFields[] = 'phone';
            }
            // 3. Same full_name + same company = 80%
            elseif ($talent->full_name === $match->full_name && $talent->current_company && $talent->current_company === $match->current_company) {
                $confidence = 80;
                $matchingFields[] = ['full_name', 'current_company'];
            }
            // 4. Same full_name + same department = 70%
            elseif ($talent->full_name === $match->full_name && $talent->department_id && $talent->department_id === $match->department_id) {
                $confidence = 70;
                $matchingFields[] = ['full_name', 'department_id'];
            }
            // 5. Similar name + same email domain = 60%
            elseif ($talent->email && $match->email) {
                $talentDomain = explode('@', $talent->email)[1] ?? null;
                $matchDomain = explode('@', $match->email)[1] ?? null;
                
                if ($talentDomain && $talentDomain === $matchDomain) {
                    $distance = levenshtein($talent->full_name, $match->full_name);
                    if ($distance <= 3) {
                        $confidence = 60;
                        $matchingFields[] = ['full_name_similar', 'email_domain'];
                    }
                }
            }

            if ($confidence >= 60) {
                $originalId = min($talent->id, $match->id);
                $duplicateId = max($talent->id, $match->id);

                // Check if already merged or exists
                $existingDuplicate = Duplicate::where(function ($q) use ($originalId, $duplicateId) {
                    $q->where('original_talent_id', $originalId)
                      ->where('duplicate_talent_id', $duplicateId);
                })->first();

                if (!$existingDuplicate || $existingDuplicate->status === 'pending') {
                    $duplicateRecord = Duplicate::updateOrCreate(
                        [
                            'original_talent_id' => $originalId,
                            'duplicate_talent_id' => $duplicateId,
                        ],
                        [
                            'matching_fields' => json_encode($matchingFields),
                            'confidence' => max($confidence, $existingDuplicate->confidence ?? 0),
                            'status' => 'pending',
                        ]
                    );

                    // Set is_potential_duplicate to true for both
                    Talent::whereIn('id', [$originalId, $duplicateId])->update(['is_potential_duplicate' => true]);
                    
                    $duplicates->push($duplicateRecord);
                }
            }
        }

        return $duplicates;
    }
}
