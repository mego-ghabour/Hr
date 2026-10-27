<?php

namespace App\Filament\Admin\Resources\EvaluationForms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EvaluationFormsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان النموذج')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('نوع النموذج')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'manager_to_employee' => 'تقييم المدير للموظف',
                        'employee_to_manager' => 'تقييم الموظف للمدير',
                        'general_survey' => 'استبيان عام',
                        default => $state ?? 'عام',
                    })
                    ->color(fn ($state) => match ($state) {
                        'manager_to_employee' => 'info',
                        'employee_to_manager' => 'warning',
                        'general_survey' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('target_type')
                    ->label('النطاق المستهدف')
                    ->badge()
                    ->formatStateUsing(fn ($state, $record) => match ($state) {
                        'all' => 'كافة الموظفين (موحد)',
                        'department' => 'قسم: ' . ($record->department?->name ?? 'مخصص'),
                        'specific_role' => 'دور: ' . ($record->target_role ?? '-'),
                        default => 'عام',
                    })
                    ->color('gray'),

                TextColumn::make('questions_count')
                    ->label('عدد المعايير')
                    ->state(fn ($record) => is_array($record->questions) ? count($record->questions) : 0)
                    ->badge()
                    ->color('primary'),

                ToggleColumn::make('is_active')
                    ->label('مفعل')
                    ->onColor('success')
                    ->offColor('gray'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->date('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('نوع التقييم')
                    ->options([
                        'manager_to_employee' => 'تقييم المدير للموظف',
                        'employee_to_manager' => 'تقييم الموظف للمدير',
                        'general_survey' => 'استبيان عام',
                    ]),
                SelectFilter::make('target_type')
                    ->label('النطاق المستهدف')
                    ->options([
                        'all' => 'كافة الموظفين (موحد)',
                        'department' => 'قسم محدد',
                        'specific_role' => 'دور محدد',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('duplicate')
                    ->label('استنساخ')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('استنساخ نموذج التقييم')
                    ->modalDescription(fn ($record) => "هل تريد إنشاء نسخة جديدة من نموذج ({$record->title})؟")
                    ->action(function ($record): void {
                        $clone = $record->replicate();
                        $clone->title = $record->title . ' (نسخة مستنسخة)';
                        $clone->save();

                        \Filament\Notifications\Notification::make()
                            ->title('تم استنساخ النموذج بنجاح')
                            ->body('يمكنك الآن تعديل النسخة الجديدة وتخصيص معاييرها.')
                            ->success()
                            ->send();
                    }),

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
