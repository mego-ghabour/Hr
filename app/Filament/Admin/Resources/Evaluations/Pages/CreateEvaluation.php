<?php

namespace App\Filament\Admin\Resources\Evaluations\Pages;

use App\Filament\Admin\Resources\Evaluations\EvaluationResource;
use App\Models\EvaluationForm;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluation extends CreateRecord
{
    protected static string $resource = EvaluationResource::class;

    public function mount(): void
    {
        parent::mount();

        $formId = request()->query('evaluation_form_id');
        if ($formId) {
            $this->form->fill(['evaluation_form_id' => (int) $formId]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['evaluator_id'] = auth()->id();

        if (!empty($data['evaluation_form_id']) && !empty($data['answers'])) {
            $form = EvaluationForm::find($data['evaluation_form_id']);
            if ($form) {
                $data['score'] = $form->calculateScore($data['answers']);
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
