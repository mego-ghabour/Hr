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
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
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
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('موافقة')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('الموافقة على الإجازة')
                    ->modalDescription(fn ($record) => 'هل أنت متأكد من الموافقة على طلب إجازة ' . ($record->employee?->first_name ?? '') . '؟ سيتم خصم الأيام من رصيده تلقائياً.')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);
                        \Filament\Notifications\Notification::make()->title('تمت الموافقة على الإجازة بنجاح')->success()->send();
                    }),
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
                    ->visible(fn ($record) => $record->status === 'pending')
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
