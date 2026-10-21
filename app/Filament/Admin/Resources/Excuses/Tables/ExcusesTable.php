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
                    ->modalHeading('الموافقة على إذن الاستئذان')
                    ->modalDescription(fn ($record) => 'هل أنت متأكد من الموافقة على إذن استئذان ' . ($record->employee?->first_name ?? '') . '؟')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);
                        \Filament\Notifications\Notification::make()->title('تمت الموافقة على الإذن بنجاح')->success()->send();
                    }),
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
                    ->visible(fn ($record) => $record->status === 'pending')
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
