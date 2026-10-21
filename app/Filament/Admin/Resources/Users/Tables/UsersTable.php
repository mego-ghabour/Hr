<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user'),
                \Filament\Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('تم نسخ البريد الإلكتروني')
                    ->copyMessageDuration(1500),
                \Filament\Tables\Columns\TextColumn::make('roles.name')
                    ->label('الدور')
                    ->formatStateUsing(fn ($state) => \App\Filament\Admin\Resources\Roles\Schemas\RoleForm::getRoleLabel($state))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Super Admin', 'admin', 'المدير العام', 'مسؤول النظام' => 'danger',
                        'HR Manager', 'مدير الموارد البشرية' => 'success',
                        'Interviewer', 'مسؤول المقابلات' => 'warning',
                        'Employee', 'موظف' => 'gray',
                        default => 'primary',
                    })
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('الموظف المرتبط (ESS)')
                    ->badge()
                    ->color(fn ($record) => $record->employee ? 'success' : 'gray')
                    ->placeholder('غير مرتبط بموظف')
                    ->icon('heroicon-m-identification')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('employee.department.name')
                    ->label('القسم')
                    ->placeholder('—')
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنضمام')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('تصفية حسب الدور')
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn ($record) => $record && $record->id === auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            $records->reject(fn ($record) => $record->id === auth()->id())->each->delete();
                        }),
                ]),
            ]);
    }
}
