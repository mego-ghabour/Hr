<?php

namespace App\Filament\Admin\Resources\Leaves\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class LeavesTable
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
                
                TextColumn::make('leaveType.name')
                    ->label('نوع الإجازة')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                
                TextColumn::make('start_date')
                    ->label('من تاريخ')
                    ->date('Y-m-d')
                    ->sortable()
                    ->icon('heroicon-m-calendar-days'),
                
                TextColumn::make('end_date')
                    ->label('إلى تاريخ')
                    ->date('Y-m-d')
                    ->sortable()
                    ->icon('heroicon-m-calendar-days'),
                
                TextColumn::make('status')
                    ->label('حالة الطلب')
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
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('الموظف')
                    ->relationship('employee', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($r) => $r ? "{$r->first_name} {$r->last_name}" : '')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('leave_type_id')
                    ->label('نوع الإجازة')
                    ->relationship('leaveType', 'name'),
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
                        \Filament\Notifications\Notification::make()->title('تمت موافقة المدير المباشر وتحويل الطلب للموارد البشرية')->success()->send();
                    }),

                // المرحلة الثانية / الاعتماد النهائي للموارد البشرية
                \Filament\Actions\Action::make('approve')
                    ->label('اعتماد نهائي')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('الاعتماد النهائي للإجازة')
                    ->modalDescription(fn ($record) => 'هل أنت متأكد من الاعتماد النهائي لطلب إجازة ' . ($record->employee?->first_name ?? '') . '؟ سيتم خصم الأيام من رصيده تلقائياً.')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'pending_hr']))
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'hr_approved_at' => now(),
                            'hr_approved_by' => auth()->id(),
                        ]);
                        \Filament\Notifications\Notification::make()->title('تم الاعتماد النهائي للإجازة بنجاح')->success()->send();
                    }),

                // خيار إداري: الاعتماد المباشر وتخطي مرحلة المدير
                \Filament\Actions\Action::make('direct_approve')
                    ->label('اعتماد مباشر (تخطي)')
                    ->icon('heroicon-o-bolt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('اعتماد فوري وتخطي المدير')
                    ->modalDescription('سيتم تجاوز موافقة المدير المباشر واعتماد الإجازة فورياً مع خصم الرصيد.')
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
                        \Filament\Notifications\Notification::make()->title('تم الاعتماد المباشر للإجازة بنجاح')->success()->send();
                    }),

                // رفض الطلب
                \Filament\Actions\Action::make('reject')
                    ->label('رفض')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('admin_notes')
                            ->label('سبب الرفض')
                            ->placeholder('اكتب سبب رفض الإجازة...')
                            ->required(),
                    ])
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'pending_manager', 'pending_hr']))
                    ->action(function (array $data, $record) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['admin_notes'] ?? null,
                        ]);
                        \Filament\Notifications\Notification::make()->title('تم رفض طلب الإجازة')->warning()->send();
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
