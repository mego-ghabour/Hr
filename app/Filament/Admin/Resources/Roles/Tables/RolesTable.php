<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Filament\Admin\Resources\Roles\Schemas\RoleForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
