<?php

namespace App\Filament\Admin\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        // By default we use row-based list layout.
        return $table
            ->columns(self::getListColumns())
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    protected static function getListColumns(): array
    {
        return [
            ImageColumn::make('avatar_url')
                ->label('الصورة')
                ->circular()
                ->defaultImageUrl(url('https://ui-avatars.com/api/?name=Employee&color=7F9CF5&background=EBF4FF')),
            TextColumn::make('first_name')
                ->label('الاسم الأول')
                ->searchable()
                ->sortable(),
            TextColumn::make('last_name')
                ->label('الاسم الأخير')
                ->searchable()
                ->sortable(),
            TextColumn::make('department.name')
                ->label('القسم')
                ->sortable(),
            TextColumn::make('job_title')
                ->label('المسمى الوظيفي')
                ->searchable(),
            TextColumn::make('phone')
                ->label('رقم الهاتف')
                ->searchable(),
            TextColumn::make('status')
                ->label('الحالة')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'active' => 'نشط',
                    'on_leave' => 'في إجازة',
                    'terminated' => 'مستقيل / مفصول',
                    default => $state,
                })
                ->colors([
                    'success' => 'active',
                    'warning' => 'on_leave',
                    'danger' => 'terminated',
                ]),
        ];
    }
}
