<?php

namespace App\Filament\Admin\Resources\EvaluationForms\Pages;

use App\Filament\Admin\Resources\EvaluationForms\EvaluationFormResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluationForm extends CreateRecord
{
    protected static string $resource = EvaluationFormResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
