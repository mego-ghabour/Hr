<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Admin\Resources\Roles\Schemas\RoleForm;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        foreach (RoleForm::getPermissionGroups() as $group) {
            unset($data[$group['field']]);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $allPerms = [];
        foreach (RoleForm::getPermissionGroups() as $group) {
            $field = $group['field'];
            if (!empty($this->data[$field]) && is_array($this->data[$field])) {
                $allPerms = array_merge($allPerms, $this->data[$field]);
            }
        }

        if (!empty($allPerms)) {
            $this->record->syncPermissions(array_unique($allPerms));
        }
    }
}
