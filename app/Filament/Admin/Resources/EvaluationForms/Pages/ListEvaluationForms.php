<?php

namespace App\Filament\Admin\Resources\EvaluationForms\Pages;

use App\Filament\Admin\Resources\EvaluationForms\EvaluationFormResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvaluationForms extends ListRecords
{
    protected static string $resource = EvaluationFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('إنشاء نموذج تقييم جديد')
                ->icon('heroicon-o-plus'),
        ];
    }
}
