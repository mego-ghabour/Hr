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
                \Filament\Actions\Action::make('approve')
                    ->label('اعتماد')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->requiresConfirmation()
                    ->modalHeading('اعتماد التقييم رسمياً')
                    ->modalDescription(fn ($record) => "هل تريد اعتماد نتيجة تقييم ({$record->evaluatedEmployee?->full_name}) بدرجة (" . number_format((float)$record->score, 2) . "/5.0)؟")
                    ->action(function ($record): void {
                        $record->update(['status' => 'reviewed']);

                        \Filament\Notifications\Notification::make()
                            ->title('تم اعتماد التقييم بنجاح')
                            ->success()
                            ->send();
                    }),

                \Filament\Actions\Action::make('view_summary')
                    ->label('تفاصيل')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'ملخص تقييم: ' . ($record->evaluatedEmployee?->full_name ?? ''))
                    ->modalDescription(fn ($record) => 'النموذج: ' . ($record->form?->title ?? '') . ' | الفترة: ' . $record->period)
                    ->form([
                        \Filament\Forms\Components\TextInput::make('score_display')
                            ->label('الدرجة الإجمالية')
                            ->default(fn ($record) => number_format((float)$record->score, 2) . ' من 5.0 نجوم ⭐')
                            ->disabled(),

                        \Filament\Forms\Components\TextInput::make('evaluator_display')
                            ->label('المُقيّم')
                            ->default(fn ($record) => $record->display_evaluator_name)
                            ->disabled(),

                        \Filament\Forms\Components\Textarea::make('strengths_display')
                            ->label('نقاط القوة والإنجازات')
                            ->default(fn ($record) => $record->strengths ?: 'لا توجد ملاحظات مسجلة')
                            ->disabled()
                            ->rows(3),

                        \Filament\Forms\Components\Textarea::make('improvements_display')
                            ->label('نقاط التحسين والتطوير')
                            ->default(fn ($record) => $record->areas_for_improvement ?: 'لا توجد ملاحظات مسجلة')
                            ->disabled()
                            ->rows(3),

                        \Filament\Forms\Components\Textarea::make('action_plan_display')
                            ->label('خطة العمل المقترحة (Action Plan)')
                            ->default(fn ($record) => $record->action_plan ?: 'لا توجد خطة مسجلة')
                            ->disabled()
                            ->rows(3),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('إغلاق'),

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
