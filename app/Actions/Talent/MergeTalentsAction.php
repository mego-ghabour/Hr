<?php

namespace App\Actions\Talent;

use App\Models\Duplicate;
use App\Models\Talent;
use Illuminate\Support\Facades\DB;

class MergeTalentsAction
{
    public function execute(Talent $primary, Talent $duplicate): Talent
    {
        return DB::transaction(function () use ($primary, $duplicate) {
            // 1. Fill empty fields on primary with values from duplicate
            $fillable = $primary->getFillable();
            foreach ($fillable as $field) {
                if (empty($primary->{$field}) && !empty($duplicate->{$field})) {
                    $primary->{$field} = $duplicate->{$field};
                }
            }
            $primary->save();

            // 2. Transfer relationships
            if (method_exists($duplicate, 'notes')) {
                $duplicate->notes()->update(['talent_id' => $primary->id]);
            }
            if (method_exists($duplicate, 'reviews')) {
                $duplicate->reviews()->update(['talent_id' => $primary->id]);
            }
            if (method_exists($duplicate, 'followUps')) {
                $duplicate->followUps()->update(['talent_id' => $primary->id]);
            }
            if (method_exists($duplicate, 'documents')) {
                $duplicate->documents()->update(['talent_id' => $primary->id]);
            }
            if (method_exists($duplicate, 'tags') && method_exists($primary, 'tags')) {
                $duplicateTags = $duplicate->tags()->pluck('id')->toArray();
                $primary->tags()->syncWithoutDetaching($duplicateTags);
            }

            // 3. Add a note to the primary talent
            if (method_exists($primary, 'notes')) {
                $primary->notes()->create([
                    'content' => "Merged from talent ID {$duplicate->id}",
                ]);
            }

            // 4. Update the Duplicate record status to 'merged'
            Duplicate::where('original_talent_id', $primary->id)
                ->where('duplicate_talent_id', $duplicate->id)
                ->update(['status' => 'merged']);
            
            Duplicate::where('original_talent_id', $duplicate->id)
                ->where('duplicate_talent_id', $primary->id)
                ->update(['status' => 'merged']);

            // 5. Soft-delete the duplicate talent
            $duplicate->delete();

            // 6. Log the merge activity using Spatie Activitylog
            activity()
                ->performedOn($primary)
                ->withProperties(['merged_talent_id' => $duplicate->id])
                ->log('Merged duplicate talent');

            // 7. Return the refreshed primary talent
            return $primary->refresh();
        });
    }
}
