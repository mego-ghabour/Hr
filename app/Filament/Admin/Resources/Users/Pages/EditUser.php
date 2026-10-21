<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\Employee;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->hidden(fn () => $this->record->id === auth()->id()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['employee_id'] = $this->record->employee?->id;

        return $data;
    }

    protected function afterSave(): void
    {
        $employeeId = $this->data['employee_id'] ?? null;

        // إزالة الربط السابق للمستخدم إن وجد
        Employee::where('user_id', $this->record->id)->update(['user_id' => null]);

        // ربط الموظف الجديد المحدد
        if ($employeeId) {
            Employee::where('id', $employeeId)->update(['user_id' => $this->record->id]);
        }
    }
}
