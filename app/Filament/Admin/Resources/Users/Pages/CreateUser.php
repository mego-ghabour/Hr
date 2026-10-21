<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\Employee;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $employeeId = $this->data['employee_id'] ?? null;
        if ($employeeId) {
            Employee::where('user_id', $this->record->id)->update(['user_id' => null]);
            Employee::where('id', $employeeId)->update(['user_id' => $this->record->id]);
        }
    }
}
