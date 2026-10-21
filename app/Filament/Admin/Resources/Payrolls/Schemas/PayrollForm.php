<?php

namespace App\Filament\Admin\Resources\Payrolls\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الموظف والفترة')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('employee_id')
                                ->label('الموظف')
                                ->relationship('employee', 'first_name')
                                ->getOptionLabelFromRecordUsing(fn ($record) => $record ? "{$record->first_name} {$record->last_name} ({$record->job_title})" : '')
                                ->searchable(['first_name', 'last_name', 'job_title'])
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if ($state) {
                                        $emp = Employee::find($state);
                                        if ($emp && $emp->salary) {
                                            $set('basic_salary', $emp->salary);
                                            $allowances = 0;
                                            $deductions = 0;
                                            $set('net_salary', $emp->salary + $allowances - $deductions);
                                        }
                                    }
                                }),

                            Select::make('month')
                                ->label('عن شهر')
                                ->options([
                                    1 => 'يناير (01)',
                                    2 => 'فبراير (02)',
                                    3 => 'مارس (03)',
                                    4 => 'أبريل (04)',
                                    5 => 'مايو (05)',
                                    6 => 'يونيو (06)',
                                    7 => 'يوليو (07)',
                                    8 => 'أغسطس (08)',
                                    9 => 'سبتمبر (09)',
                                    10 => 'أكتوبر (10)',
                                    11 => 'نوفمبر (11)',
                                    12 => 'ديسمبر (12)',
                                ])
                                ->default(now()->month)
                                ->required(),

                            TextInput::make('year')
                                ->label('السنة')
                                ->numeric()
                                ->default(now()->year)
                                ->required(),
                        ]),
                    ]),

                Section::make('التفاصيل المالية للراتب')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('basic_salary')
                                ->label('الراتب الأساسي')
                                ->numeric()
                                ->prefix(fn () => \App\Models\Setting::currencySymbol())
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $basic = (float) ($get('basic_salary') ?? 0);
                                    $allow = (float) ($get('allowances') ?? 0);
                                    $deduct = (float) ($get('deductions') ?? 0);
                                    $set('net_salary', round($basic + $allow - $deduct, 2));
                                }),

                            TextInput::make('allowances')
                                ->label('البدلات والحوافز (+)')
                                ->numeric()
                                ->default(0)
                                ->prefix(fn () => \App\Models\Setting::currencySymbol())
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $basic = (float) ($get('basic_salary') ?? 0);
                                    $allow = (float) ($get('allowances') ?? 0);
                                    $deduct = (float) ($get('deductions') ?? 0);
                                    $set('net_salary', round($basic + $allow - $deduct, 2));
                                }),

                            TextInput::make('deductions')
                                ->label('الخصومات والغياب (-)')
                                ->numeric()
                                ->default(0)
                                ->prefix(fn () => \App\Models\Setting::currencySymbol())
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $basic = (float) ($get('basic_salary') ?? 0);
                                    $allow = (float) ($get('allowances') ?? 0);
                                    $deduct = (float) ($get('deductions') ?? 0);
                                    $set('net_salary', round($basic + $allow - $deduct, 2));
                                }),

                            TextInput::make('net_salary')
                                ->label('صافي الراتب المستحق')
                                ->numeric()
                                ->prefix(fn () => \App\Models\Setting::currencySymbol())
                                ->readOnly()
                                ->helperText('يُحسب تلقائياً (الأساسي + البدلات - الخصومات)'),
                        ]),
                    ]),

                Section::make('حالة الصرف والاعتماد')
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('status')
                                ->label('حالة المسير')
                                ->options([
                                    'draft' => 'مسودة',
                                    'approved' => 'معتمد',
                                    'paid' => 'مدفوع تم الصرف',
                                ])
                                ->default('draft')
                                ->required()
                                ->reactive(),

                            Select::make('payment_method')
                                ->label('طريقة الصرف')
                                ->options([
                                    'bank_transfer' => 'تحويل بنكي',
                                    'cash' => 'نقداً',
                                    'cheque' => 'شيك مصرفي',
                                ])
                                ->default('bank_transfer')
                                ->required(),

                            DatePicker::make('payment_date')
                                ->label('تاريخ الصرف')
                                ->default(now()),
                        ]),

                        Textarea::make('notes')
                            ->label('ملاحظات إضافية')
                            ->placeholder('أي تفاصيل حول الخصومات أو المكافآت...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
