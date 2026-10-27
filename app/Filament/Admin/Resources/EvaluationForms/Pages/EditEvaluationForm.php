<?php

namespace App\Filament\Admin\Resources\EvaluationForms\Pages;

use App\Filament\Admin\Resources\EvaluationForms\EvaluationFormResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvaluationForm extends EditRecord
{
    protected static string $resource = EvaluationFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
