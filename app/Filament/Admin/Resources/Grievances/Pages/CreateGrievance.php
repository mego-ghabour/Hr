<?php

namespace App\Filament\Admin\Resources\Grievances\Pages;

use App\Filament\Admin\Resources\Grievances\GrievanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGrievance extends CreateRecord
{
    protected static string $resource = GrievanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if (empty($data['employee_id']) && auth()->user()->employee) {
            $data['employee_id'] = auth()->user()->employee->id;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
