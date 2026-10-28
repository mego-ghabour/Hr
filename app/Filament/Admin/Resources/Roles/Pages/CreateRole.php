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
        unset($data['active_modules']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $modules = $this->data['active_modules'] ?? null;
        if (!empty($modules)) {
            if (is_string($modules)) {
                $modules = json_decode($modules, true) ?: [];
            }
            \App\Services\PermissionModuleService::syncModulesToRecord($this->record, $modules);
            return;
        }

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
