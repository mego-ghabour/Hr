<?php

namespace App\Filament\Admin\Resources\Excuses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExcusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('اسم الموظف')
                    ->state(fn ($record) => $record->employee ? $record->employee->first_name . ' ' . $record->employee->last_name : 'غير معروف')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('employee', function (Builder $query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(['employee.first_name'])
                    ->icon('heroicon-m-user'),
                
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('Y-m-d')
                    ->sortable(),
                
                TextColumn::make('start_time')
                    ->label('الخروج')
                    ->time('h:i A')
                    ->sortable(),
                
                TextColumn::make('end_time')
                    ->label('العودة')
                    ->time('h:i A')
                    ->sortable(),
                
                TextColumn::make('duration_minutes')
                    ->label('المدة')
                    ->numeric()
                    ->suffix(' دقيقة')
                    ->sortable(),
                
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending_manager' => 'بانتظار موافقة المدير المباشر',
                        'pending_hr' => 'بانتظار موافقة الـ HR',
                        'pending' => 'قيد الانتظار',
                        'approved' => 'معتمد نهائياً',
                        'rejected' => 'مرفوض',
                        default => $state,
                    })
                    ->colors([
                        'warning' => ['pending', 'pending_manager'],
                        'info' => 'pending_hr',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                
                TextColumn::make('reason')
                    ->label('السبب')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('الموظف')
                    ->relationship('employee', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($r) => $r ? "{$r->first_name} {$r->last_name}" : '')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('تصفية بالحالة')
                    ->options([
                        'pending_manager' => 'بانتظار موافقة المدير المباشر',
                        'pending_hr' => 'بانتظار موافقة الموارد البشرية',
                        'pending' => 'قيد الانتظار',
                        'approved' => 'معتمد نهائياً',
                        'rejected' => 'مرفوض',
                    ]),
            ])
            ->recordActions([
                // المرحلة الأولى: موافقة المدير المباشر
                \Filament\Actions\Action::make('manager_approve')
                    ->label('موافقة المدير')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'pending_manager')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('manager_notes')
                            ->label('توصيات وملاحظات المدير')
                            ->placeholder('ملاحظات اختيارية...')
                            ->rows(3),
                    ])
                    ->action(function (array $data, $record) {
                        $record->update([
                            'status' => 'pending_hr',
                            'manager_approved_at' => now(),
                            'manager_approved_by' => auth()->id(),
                            'manager_notes' => $data['manager_notes'] ?? null,
                        ]);
                        \Filament\Notifications\Notification::make()->title('تمت موافقة المدير المباشر وتحويل الإذن للموارد البشرية')->success()->send();
                    }),

                // المرحلة الثانية / الاعتماد النهائي للموارد البشرية
                \Filament\Actions\Action::make('approve')
                    ->label('اعتماد نهائي')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('الاعتماد النهائي لإذن الاستئذان')
                    ->modalDescription(fn ($record) => 'هل أنت متأكد من الاعتماد النهائي لإذن استئذان ' . ($record->employee?->first_name ?? '') . '؟')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'pending_hr']))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'hr_approved_at' => now(),
                            'hr_approved_by' => auth()->id(),
                        ]);
                        \Filament\Notifications\Notification::make()->title('تم الاعتماد النهائي للإذن بنجاح')->success()->send();
                    }),

                // خيار إداري: الاعتماد المباشر وتخطي مرحلة المدير
                \Filament\Actions\Action::make('direct_approve')
                    ->label('اعتماد مباشر (تخطي)')
                    ->icon('heroicon-o-bolt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('اعتماد فوري وتخطي المدير')
                    ->modalDescription('سيتم تجاوز موافقة المدير المباشر واعتماد إذن الاستئذان فورياً.')
                    ->visible(fn ($record) => $record->status === 'pending_manager')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'manager_approved_at' => now(),
                            'manager_approved_by' => auth()->id(),
                            'hr_approved_at' => now(),
                            'hr_approved_by' => auth()->id(),
                            'admin_notes' => 'تم الاعتماد المباشر من الإدارة العليا',
                        ]);
                        \Filament\Notifications\Notification::make()->title('تم الاعتماد المباشر للإذن بنجاح')->success()->send();
                    }),

                // رفض الطلب
                \Filament\Actions\Action::make('reject')
                    ->label('رفض')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('admin_notes')
                            ->label('سبب الرفض')
                            ->placeholder('اكتب سبب رفض الإذن...')
                            ->required(),
                    ])
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'pending_manager', 'pending_hr']))
                    ->action(function (array $data, $record) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['admin_notes'] ?? null,
                        ]);
                        \Filament\Notifications\Notification::make()->title('تم رفض إذن الاستئذان')->warning()->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
