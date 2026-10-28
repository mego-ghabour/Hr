<?php

namespace App\Filament\Admin\Resources\Grievances\Pages;

use App\Filament\Admin\Resources\Grievances\GrievanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGrievance extends CreateRecord
{
    protected static string $resource = GrievanceResource::class;

    public function getTitle(): string
    {
        return 'تسجيل شكوى أو مقترح جديد';
    }

    public function getHeading(): string
    {
        return 'تسجيل شكوى أو مقترح جديد';
    }

    public function getSubheading(): ?string
    {
        return 'تقديم طلب رسمي أو فكرة تطويرية بسرية وحيادية تامة تضمن حقوق الموظف وبيئة العمل.';
    }

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
