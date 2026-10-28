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
                // 1. Core Grievance Content & Classification
                Section::make('معلومات ونوع الشكوى أو المقترح')
                    ->description('حدد تصنيف الطلب ودرجة الأهمية، وبيانات الموضوع والمحتوى بالتفصيل.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('type')
                                ->label('تصنيف الطلب / الشكوى')
                                ->options([
                                    'administrative' => '🏛️ إدارية أو مالية (رواتب، إجازات، ساعات عمل)',
                                    'workplace' => '🏢 بيئة العمل والأدوات (مكاتب، أجهزة، تكييف)',
                                    'behavioral' => '🤝 سلوكية أو نزاع مهني (معاملة، بيئة الفريق)',
                                    'suggestion' => '💡 فكرة أو مقترح تطويري للعمل',
                                    'other' => '📌 موضوع آخر',
                                ])
                                ->required()
                                ->default('administrative')
                                ->native(false),

                            Select::make('priority')
                                ->label('مستوى الأهمية والاستعجال')
                                ->options([
                                    'low' => '🟢 منخفضة (استفسار / مقترح عادي)',
                                    'normal' => '🔵 عادية (طبيعية)',
                                    'high' => '🟠 عالية (تؤثر على سير العمل)',
                                    'urgent' => '🔴 عاجلة جداً (أمر حرج وطارئ ⚠️)',
                                ])
                                ->required()
                                ->default('normal')
                                ->native(false),

                            TextInput::make('title')
                                ->label('موضوع الشكوى / عنوان المقترح')
                                ->placeholder('اكتب عنواناً موجزاً ومحدداً يلخص الموضوع...')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Textarea::make('content')
                                ->label('التفاصيل والوقائع الكاملة')
                                ->placeholder('يرجى ذكر الوقائع والتواريخ وأي تفاصيل داعمة للموضوع بوضوح وأمانة لمساعدة فريق المتابعة...')
                                ->required()
                                ->rows(5)
                                ->columnSpanFull(),

                            FileUpload::make('attachment_path')
                                ->label('المرفقات والوثائق المؤيدة (اختياري)')
                                ->directory('grievance-attachments')
                                ->downloadable()
                                ->openable()
                                ->maxSize(10240)
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->helperText('الملفات المدعومة: صور، مستندات PDF أو Word بحد أقصى 10 ميجابايت.')
                                ->columnSpanFull(),
                        ]),
                    ]),

                // 2. Privacy & Submitter Governance
                Section::make('الخصوصية وهوية مقدم الطلب')
                    ->description('معايير الحوكمة والسرية وحماية مقدم الشكوى من أي تعارض مصالح.')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('is_anonymous')
                                ->label('شكوى سرية ومجهولة الهوية 🔒')
                                ->helperText('عند التفعيل، تُحجب هوية الموظف بالكامل لضمان الأمان والحيادية التامة.')
                                ->default(false)
                                ->onColor('success')
                                ->live(),

                            Select::make('employee_id')
                                ->label('الموظف مقدم الشكوى')
                                ->options(fn () => Employee::all()->pluck('full_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get) => (bool)$get('is_anonymous'))
                                ->helperText(fn (Get $get) => $get('is_anonymous') ? 'معطل لأن الشكوى محددة كسرية مجهولة الهوية.' : 'اختر الموظف صاحب الشكوى أو اتركه فارغاً.')
                                ->native(false),

                            Toggle::make('against_manager')
                                ->label('الشكوى متعلقة بالإدارة المباشرة ⚠️')
                                ->helperText('عند تفعيل هذا الخيار، يتم حجب الشكوى فوراً عن المدير المباشر وتوجيهها حصراً لإدارة الموارد البشرية والإدارة العليا لمنع أي تعارض مصالح.')
                                ->onColor('danger')
                                ->default(false)
                                ->columnSpanFull(),

                            TextInput::make('submitter_name')
                                ->label('اسم مقدم الشكوى (مُقدّم من الرابط الخارجي)')
                                ->disabled()
                                ->dehydrated(false)
                                ->visible(fn ($record) => ! empty($record?->submitter_name)),

                            TextInput::make('submitter_contact')
                                ->label('بيانات الاتصال (الهاتف / البريد من الرابط الخارجي)')
                                ->disabled()
                                ->dehydrated(false)
                                ->visible(fn ($record) => ! empty($record?->submitter_contact)),
                        ]),
                    ]),

                // 3. HR Investigation & Resolution (Visible when editing)
                Section::make('معالجة الشكوى وقرارات الموارد البشرية')
                    ->description('توثيق الإجراءات الإدارية، المحقق المسؤول، وقرار الحل النهائي.')
                    ->icon('heroicon-o-check-badge')
                    ->visible(fn ($record) => $record !== null)
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('ticket_number')
                                ->label('رقم التذكرة / الشكوى')
                                ->disabled()
                                ->dehydrated(false),

                            Select::make('status')
                                ->label('حالة الشكوى')
                                ->options([
                                    'new' => 'جديدة (غير معالجة)',
                                    'in_progress' => 'قيد المراجعة والتحقيق ⏳',
                                    'resolved' => 'تم الحل والمعالجة بنجاح ✓',
                                    'rejected' => 'مرفوضة / مغلقة دون إجراء',
                                ])
                                ->required()
                                ->default('new')
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state === 'resolved') {
                                        $set('resolved_at', now()->format('Y-m-d H:i:s'));
                                    }
                                })
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
