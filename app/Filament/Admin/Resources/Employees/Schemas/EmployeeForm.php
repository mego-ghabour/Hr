<?php

namespace App\Filament\Admin\Resources\Employees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;

class EmployeeForm
{
    public static function configure(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(static::schema());
    }

    public static function schema(): array
    {
        return [
            \Filament\Schemas\Components\Tabs::make('EmployeeTabs')
                ->tabs([
                    \Filament\Schemas\Components\Tabs\Tab::make('البيانات الشخصية')
                        ->icon('heroicon-o-user')
                        ->schema([
                            \Filament\Forms\Components\FileUpload::make('avatar_url')
                                ->label('الصورة الشخصية')
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper()
                                ->columnSpanFull()
                                ->directory('employee-avatars'),
                            Grid::make(2)->schema([
                                TextInput::make('first_name')
                                    ->label('الاسم الأول')
                                    ->required(),
                                TextInput::make('last_name')
                                    ->label('الاسم الأخير')
                                    ->required(),
                                TextInput::make('national_id')
                                    ->label('الرقم القومي / الهوية')
                                    ->numeric()
                                    ->minLength(10)
                                    ->maxLength(14)
                                    ->unique(ignoreRecord: true),
                                TextInput::make('phone')
                                    ->label('رقم الموبايل')
                                    ->tel()
                                    ->regex('/^\+?[0-9][0-9\s\-\(\)]{7,20}$/'),
                                DatePicker::make('date_of_birth')
                                    ->label('تاريخ الميلاد'),
                                Select::make('gender')
                                    ->label('الجنس')
                                    ->options([
                                        'male' => 'ذكر',
                                        'female' => 'أنثى',
                                    ]),
                                Select::make('marital_status')
                                    ->label('الحالة الاجتماعية')
                                    ->options([
                                        'single' => 'أعزب',
                                        'married' => 'متزوج',
                                    ]),
                                \Filament\Forms\Components\Textarea::make('address')
                                    ->label('العنوان')
                                    ->columnSpanFull(),
                            ]),
                        ]),
                    
                    \Filament\Schemas\Components\Tabs\Tab::make('البيانات الوظيفية')
                        ->icon('heroicon-o-briefcase')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('department_id')
                                    ->label('القسم')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('manager_id')
                                    ->label('المدير المباشر')
                                    ->relationship('manager', 'first_name')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('job_title')
                                    ->label('المسمى الوظيفي'),
                                TextInput::make('salary')
                                    ->label('الراتب الأساسي')
                                    ->numeric()
                                    ->prefix('$'),
                                DatePicker::make('hire_date')
                                    ->label('تاريخ التعيين'),
                                DatePicker::make('contract_end_date')
                                    ->label('تاريخ انتهاء العقد')
                                    ->after('hire_date'),
                                TextInput::make('social_insurance_number')
                                    ->label('رقم التأمين الاجتماعي'),
                            ]),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make('بيانات الطوارئ')
                        ->icon('heroicon-o-phone-arrow-up-right')
                        ->schema([
                            Grid::make(3)->schema([
                                TextInput::make('emergency_contact_name')
                                    ->label('اسم جهة الاتصال'),
                                TextInput::make('emergency_contact_phone')
                                    ->label('رقم الموبايل')
                                    ->tel()
                                    ->regex('/^\+?[0-9][0-9\s\-\(\)]{7,20}$/'),
                                TextInput::make('emergency_contact_relationship')
                                    ->label('صلة القرابة'),
                            ]),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make('البيانات البنكية')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('bank_name')
                                    ->label('اسم البنك'),
                                TextInput::make('iban')
                                    ->label('رقم الحساب / الايبان (IBAN)'),
                            ]),
                        ]),

                    \Filament\Schemas\Components\Tabs\Tab::make('إعدادات الموظف')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('user_id')
                                    ->label('حساب الموظف (User)')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('اختياري: اربط الموظف بحساب ليدخل به على النظام.'),
                                Select::make('status')
                                    ->label('حالة الموظف')
                                    ->options([
                                        'active' => 'نشط (على رأس العمل)',
                                        'on_leave' => 'في إجازة',
                                        'terminated' => 'مستقيل / مفصول',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ]),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }
}
