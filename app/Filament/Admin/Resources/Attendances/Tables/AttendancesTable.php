<?php

namespace App\Filament\Admin\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
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
                    ->sortable()
                    ->icon('heroicon-m-calendar'),
                
                TextColumn::make('check_in_time')
                    ->label('الحضور')
                    ->time('h:i A')
                    ->placeholder('لم يسجل')
                    ->sortable()
                    ->icon('heroicon-m-arrow-right-on-rectangle')
                    ->color('success'),
                
                TextColumn::make('check_out_time')
                    ->label('الانصراف')
                    ->time('h:i A')
                    ->placeholder('لم يسجل')
                    ->sortable()
                    ->icon('heroicon-m-arrow-left-on-rectangle')
                    ->color('danger'),

                TextColumn::make('total_hours')
                    ->label('ساعات العمل')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-')
                    ->icon('heroicon-m-clock'),

                TextColumn::make('is_remote')
                    ->label('نوع البصمة / الموقع')
                    ->badge()
                    ->state(function ($record) {
                        if ($record->is_remote) {
                            return 'عن بُعد 🏠';
                        }
                        if (!is_null($record->distance_meters)) {
                            return 'مقر العمل (' . round($record->distance_meters) . ' م) 📍';
                        }
                        return 'مباشر';
                    })
                    ->color(fn ($record) => $record->is_remote ? 'info' : (!is_null($record->distance_meters) ? 'success' : 'gray')),
                
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'half_day' => 'نصف يوم',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'present',
                        'danger' => 'absent',
                        'warning' => 'late',
                        'info' => 'half_day',
                    ]),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('الموظف')
                    ->relationship('employee', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record ? "{$record->first_name} {$record->last_name}" : '')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('تصفية بالحالة')
                    ->options([
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'half_day' => 'نصف يوم',
                    ]),
                Filter::make('date_range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('من تاريخ'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('date', '<=', $date));
                    }),
            ])
            ->recordActions([
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
