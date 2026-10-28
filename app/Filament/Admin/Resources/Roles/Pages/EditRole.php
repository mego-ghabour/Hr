<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Admin\Resources\Roles\Schemas\RoleForm;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->hidden(fn () => in_array($this->record->name, ['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام'])),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $currentPerms = $this->record->permissions->pluck('name')->toArray();

        foreach (RoleForm::getPermissionGroups() as $group) {
            $field = $group['field'];
            $groupKeys = array_keys($group['permissions']);
            $data[$field] = array_values(array_intersect($currentPerms, $groupKeys));
        }

        $data['active_modules'] = \App\Services\PermissionModuleService::getActiveModulesForRecord($this->record);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        foreach (RoleForm::getPermissionGroups() as $group) {
            unset($data[$group['field']]);
        }
        unset($data['active_modules']);

        return $data;
    }

    protected function afterSave(): void
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
            if (isset($this->data[$field]) && is_array($this->data[$field])) {
                $allPerms = array_merge($allPerms, $this->data[$field]);
            }
        }

        $this->record->syncPermissions(array_unique($allPerms));
    }
}
