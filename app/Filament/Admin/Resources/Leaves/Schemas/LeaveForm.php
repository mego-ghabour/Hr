<?php

namespace App\Filament\Admin\Resources\Leaves\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class LeaveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('تفاصيل طلب الإجازة')
                    ->description('إدخال نوع وتاريخ الإجازة للموظف')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('employee_id')
                                ->label('الموظف')
                                ->relationship('employee', 'first_name')
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                                ->searchable(['first_name', 'last_name'])
                                ->preload()
                                ->required(),
                            Select::make('leave_type_id')
                                ->label('نوع الإجازة')
                                ->relationship('leaveType', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            DatePicker::make('start_date')
                                ->label('تاريخ البداية')
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('تاريخ النهاية')
                                ->required(),
                            Select::make('status')
                                ->label('حالة الطلب')
                                ->options([
                                    'pending' => 'قيد الانتظار',
                                    'approved' => 'تمت الموافقة',
                                    'rejected' => 'مرفوض',
                                ])
                                ->default('pending')
                                ->required(),
                        ]),
                        Textarea::make('reason')
                            ->label('سبب الإجازة')
                            ->placeholder('أسباب أو مبررات طلب الإجازة...')
                            ->columnSpanFull(),
                        Textarea::make('admin_notes')
                            ->label('ملاحظات الإدارة')
                            ->placeholder('ملاحظات الموارد البشرية (إن وجدت)...')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
