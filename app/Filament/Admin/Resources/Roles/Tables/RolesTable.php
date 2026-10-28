<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Filament\Admin\Resources\Roles\Schemas\RoleForm;
use App\Services\PermissionModuleService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدور')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Super Admin', 'admin' => 'danger',
                        'HR Manager' => 'success',
                        'Interviewer' => 'warning',
                        'Employee' => 'gray',
                        default => 'primary',
                    }),
                \Filament\Tables\Columns\TextColumn::make('permissions.name')
                    ->label('الصلاحيات الممنوحة')
                    ->formatStateUsing(fn (string $state): string => RoleForm::getPermissionLabel($state))
                    ->badge()
                    ->limitList(4)
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('managePermissions')
                    ->label('إدارة الصلاحيات')
                    ->icon('heroicon-o-shield-check')
                    ->color('primary')
                    ->modalHeading('')
                    ->modalWidth('2xl')
                    ->modalSubmitActionLabel('حفظ الصلاحيات')
                    ->modalCancelActionLabel('إلغاء')
                    ->fillForm(fn ($record) => [
                        'active_modules' => PermissionModuleService::getActiveModulesForRecord($record),
                    ])
                    ->form([
                        ViewField::make('active_modules')
                            ->view('filament.admin.components.permission-matrix-modal')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, $record) {
                        $modules = $data['active_modules'] ?? [];
                        if (is_string($modules)) {
                            $modules = json_decode($modules, true) ?: [];
                        }
                        PermissionModuleService::syncModulesToRecord($record, $modules);
                        Notification::make()
                            ->title('تم حفظ الصلاحيات بنجاح')
                            ->body('تم تحديث صلاحيات الدور ' . RoleForm::getRoleLabel($record->name))
                            ->success()
                            ->send();
                    }),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record && in_array($record->name, ['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            $records->reject(fn ($record) => in_array($record->name, ['Super Admin', 'admin', 'المدير العام', 'مسؤول النظام']))->each->delete();
                        }),
                ]),
            ]);
    }
}
