<?php

namespace App\Filament\Admin\Resources\Evaluations\Tables;

use App\Models\Employee;
use App\Models\EvaluationForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('evaluatedEmployee.full_name')
                    ->label('الشخص الخاضع للتقييم')
                    ->description(fn ($record) => $record->evaluatedEmployee?->job_title ?? '')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('form.title')
                    ->label('النموذج')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('score')
                    ->label('النتيجة الإجمالية')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if ($state === null) return 'غير محتسب';
                        return number_format((float)$state, 2) . ' / 5.0 ⭐';
                    })
                    ->color(fn ($state) => match (true) {
                        $state >= 4.0 => 'success',
                        $state >= 3.0 => 'warning',
                        $state > 0 => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('display_evaluator_name')
                    ->label('المُقيّم')
                    ->badge()
                    ->color(fn ($record) => $record->is_anonymous ? 'gray' : 'info'),

                TextColumn::make('period')
                    ->label('الفترة')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'مسودة',
                        'submitted' => 'تم التقديم',
                        'reviewed' => 'معتمد',
                        default => $state ?? '-',
                    })
                    ->color(fn ($state) => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'reviewed' => 'success',
                        default => 'primary',
                    }),

                TextColumn::make('created_at')
                    ->label('تاريخ التقييم')
                    ->date('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('evaluation_form_id')
                    ->label('النموذج')
                    ->options(fn () => EvaluationForm::pluck('title', 'id')),

                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'submitted' => 'تم التقديم',
                        'reviewed' => 'معتمد',
                    ]),

                SelectFilter::make('evaluated_employee_id')
                    ->label('الموظف')
                    ->options(fn () => Employee::all()->pluck('full_name', 'id'))
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
