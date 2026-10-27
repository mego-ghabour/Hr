<?php

namespace App\Filament\Admin\Resources\Grievances\Tables;

use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GrievancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')
                    ->label('رقم التذكرة')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('title')
                    ->label('موضوع الشكوى')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->title),

                TextColumn::make('display_submitter_name')
                    ->label('مقدم الشكوى')
                    ->badge()
                    ->color(fn ($record) => $record->is_anonymous ? 'gray' : 'info'),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'administrative' => 'إدارية / مالية',
                        'workplace' => 'بيئة العمل',
                        'behavioral' => 'سلوكية / نزاع',
                        'suggestion' => 'مقترح تطويري',
                        default => 'أخرى',
                    })
                    ->color(fn ($state) => match ($state) {
                        'administrative' => 'info',
                        'workplace' => 'warning',
                        'behavioral' => 'danger',
                        'suggestion' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('priority')
                    ->label('الأهمية')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'low' => 'منخفضة',
                        'normal' => 'عادية',
                        'high' => 'عالية',
                        'urgent' => 'عاجلة جداً',
                        default => 'عادية',
                    })
                    ->color(fn ($state) => match ($state) {
                        'low' => 'gray',
                        'normal' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'new' => 'جديدة',
                        'in_progress' => 'قيد المراجعة',
                        'resolved' => 'تم الحل والمعالجة',
                        'rejected' => 'مرفوضة / مغلقة',
                        default => $state ?? 'جديدة',
                    })
                    ->color(fn ($state) => match ($state) {
                        'new' => 'danger',
                        'in_progress' => 'warning',
                        'resolved' => 'success',
                        'rejected' => 'gray',
                        default => 'gray',
                    }),

                IconColumn::make('against_manager')
                    ->label('ضد إدارة مباشرة')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-exclamation')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-minus')
                    ->falseColor('gray'),

                TextColumn::make('created_at')
                    ->label('تاريخ التقديم')
                    ->date('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'new' => 'جديدة',
                        'in_progress' => 'قيد المراجعة',
                        'resolved' => 'تم الحل والمعالجة',
                        'rejected' => 'مرفوضة / مغلقة',
                    ]),

                SelectFilter::make('type')
                    ->label('نوع الشكوى')
                    ->options([
                        'administrative' => 'إدارية / مالية',
                        'workplace' => 'بيئة العمل',
                        'behavioral' => 'سلوكية / نزاع',
                        'suggestion' => 'مقترح تطويري',
                    ]),

                SelectFilter::make('priority')
                    ->label('الأولوية')
                    ->options([
                        'low' => 'منخفضة',
                        'normal' => 'عادية',
                        'high' => 'عالية',
                        'urgent' => 'عاجلة جداً',
                    ]),
            ])
            ->recordActions([
                Action::make('quick_resolve')
                    ->label('الرد والمعالجة')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->color('success')
                    ->modalHeading('الرد على الشكوى واتخاذ الإجراء')
                    ->form([
                        Select::make('status')
                            ->label('تحديث حالة الشكوى')
                            ->options([
                                'in_progress' => 'قيد المراجعة والتحقيق',
                                'resolved' => 'تم الحل والمعالجة بنجاح',
                                'rejected' => 'مرفوضة / مغلقة دون إجراء',
                            ])
                            ->default(fn ($record) => $record->status === 'new' ? 'resolved' : $record->status)
                            ->required()
                            ->native(false),

                        Textarea::make('hr_response')
                            ->label('رد وتوجيه إدارة الموارد البشرية')
                            ->placeholder('اكتب تفاصيل الحل أو الرد على الموظف...')
                            ->default(fn ($record) => $record->hr_response)
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                            'hr_response' => $data['hr_response'],
                            'resolved_at' => $data['status'] === 'resolved' ? now() : $record->resolved_at,
                            'assigned_to' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('تم تحديث حالة الشكوى وإرسال الرد بنجاح')
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
