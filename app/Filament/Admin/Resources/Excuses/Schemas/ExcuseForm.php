<?php

namespace App\Filament\Admin\Resources\Excuses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ExcuseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('طلب استئذان / خروج مبكر')
                    ->description('أدخل تفاصيل ومبررات الاستئذان')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('employee_id')
                                ->label('الموظف')
                                ->relationship('employee', 'first_name')
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                                ->searchable(['first_name', 'last_name'])
                                ->preload()
                                ->required(),
                            DatePicker::make('date')
                                ->label('التاريخ')
                                ->default(now())
                                ->required(),
                            TimePicker::make('start_time')
                                ->label('وقت الخروج')
                                ->seconds(false)
                                ->required(),
                            TimePicker::make('end_time')
                                ->label('وقت العودة')
                                ->seconds(false)
                                ->required(),
                            TextInput::make('duration_minutes')
                                ->label('المدة (بالدقائق)')
                                ->numeric()
                                ->placeholder('مثال: 120')
                                ->default(null),
                            Select::make('status')
                                ->label('حالة الطلب')
                                ->options([
                                    'pending' => 'قيد الانتظار',
                                    'approved' => 'مقبول',
                                    'rejected' => 'مرفوض',
                                ])
                                ->default('pending')
                                ->required(),
                        ]),
                        Textarea::make('reason')
                            ->label('سبب الاستئذان')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('admin_notes')
                            ->label('ملاحظات الإدارة')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
