<?php

namespace App\Filament\Admin\Resources\Attendances\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('سجل الحضور والانصراف')
                    ->description('قم بإدخال بيانات الحضور للموظف.')
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
                                ->required()
                                ->default(now()),
                            TimePicker::make('check_in_time')
                                ->label('وقت الحضور (تسجيل الدخول)')
                                ->seconds(false),
                            TimePicker::make('check_out_time')
                                ->label('وقت الانصراف (تسجيل الخروج)')
                                ->seconds(false),
                            Select::make('status')
                                ->label('الحالة')
                                ->options([
                                    'present' => 'حاضر',
                                    'absent' => 'غائب',
                                    'late' => 'متأخر',
                                    'half_day' => 'نصف يوم',
                                ])
                                ->default('present')
                                ->required(),
                        ]),
                        Textarea::make('notes')
                            ->label('ملاحظات إضافية')
                            ->placeholder('أي ملاحظات حول تأخير أو غياب الموظف...')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
