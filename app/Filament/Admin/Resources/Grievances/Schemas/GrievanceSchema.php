<?php

namespace App\Filament\Admin\Resources\Grievances\Schemas;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class GrievanceSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('تفاصيل الشكوى أو المقترح (Grievance Details)')
                    ->description('كافة المعلومات المقدمة في الشكوى الرقمية بديلة السجلات الورقية.')
                    ->icon('heroicon-o-inbox-arrow-down')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('ticket_number')
                                ->label('رقم التذكرة / الشكوى')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('يتم التوليد تلقائياً عند الحفظ')
                                ->visible(fn ($record) => $record !== null),

                            Select::make('type')
                                ->label('نوع الطلب / الشكوى')
                                ->options([
                                    'administrative' => 'إدارية / مالية (رواتب، إجازات، ساعات عمل)',
                                    'workplace' => 'بيئة العمل والأدوات (أجهزة، تكييف، مستلزمات)',
                                    'behavioral' => 'سلوكية / نزاع مهني (معاملة، سوء تفاهم)',
                                    'suggestion' => 'فكرة أو مقترح تطويري للعمل',
                                    'other' => 'أخرى',
                                ])
                                ->required()
                                ->default('administrative')
                                ->native(false),

                            Select::make('priority')
                                ->label('مستوى الأهمية / الأولوية')
                                ->options([
                                    'low' => 'منخفضة',
                                    'normal' => 'عادية',
                                    'high' => 'عالية',
                                    'urgent' => 'عاجلة جداً ⚠️',
                                ])
                                ->required()
                                ->default('normal')
                                ->native(false),

                            Toggle::make('is_anonymous')
                                ->label('شكوى سرية تماماً (إخفاء هوية الموظف) 🔒')
                                ->helperText('عند التفعيل، لن يظهر اسم الموظف ويُسجل كـ "مجهول الهوية" لضمان الأمان والحيادية.')
                                ->default(false)
                                ->live(),

                            Select::make('employee_id')
                                ->label('الموظف مقدم الشكوى')
                                ->options(fn () => Employee::all()->pluck('full_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get) => (bool)$get('is_anonymous'))
                                ->helperText(fn (Get $get) => $get('is_anonymous') ? 'معطل لأن الشكوى سرية مجهولة الهوية.' : '')
                                ->native(false),

                            Toggle::make('against_manager')
                                ->label('الشكوى متعلقة بالإدارة المباشرة ⚠️')
                                ->helperText('في حال تفعيل هذا الخيار، يتم حجب الشكوى عن المدير المباشر وتوجيهها حصراً لإدارة الـ HR.')
                                ->default(false),

                            TextInput::make('title')
                                ->label('موضوع الشكوى / المقترح')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Textarea::make('content')
                                ->label('تفاصيل الشكوى / المقترح الكاملة')
                                ->required()
                                ->rows(5)
                                ->columnSpanFull(),

                            FileUpload::make('attachment_path')
                                ->label('المرفقات / المستندات المؤيدة')
                                ->directory('grievance-attachments')
                                ->downloadable()
                                ->openable()
                                ->maxSize(10240)
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('معالجة الشكوى وقرارات الموارد البشرية (HR Resolution)')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('status')
                                ->label('حالة الشكوى')
                                ->options([
                                    'new' => 'جديدة (غير معالجة)',
                                    'in_progress' => 'قيد المراجعة والتحقيق',
                                    'resolved' => 'تم الحل والمعالجة بنجاح ✓',
                                    'rejected' => 'مرفوضة / مغلقة دون إجراء',
                                ])
                                ->required()
                                ->default('new')
                                ->native(false),

                            Select::make('assigned_to')
                                ->label('مسؤول الـ HR المكلف بالمتابعة')
                                ->options(fn () => User::whereHas('roles', function ($q) {
                                    $q->whereIn('name', ['Super Admin', 'admin', 'مدير الموارد البشرية', 'HR Manager', 'مسؤول النظام']);
                                })->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->native(false),

                            DateTimePicker::make('resolved_at')
                                ->label('تاريخ إغلاق ومعالجة الشكوى'),

                            Textarea::make('hr_response')
                                ->label('رد إدارة الموارد البشرية والإجراء المتخذ')
                                ->placeholder('اكتب تفاصيل الإجراء الذي تم اتخاذه أو توجيهات الـ HR للموظف...')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
