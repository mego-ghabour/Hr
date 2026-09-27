<?php
$content = file_get_contents('app/Filament/Admin/Resources/JobPostingResource.php');

$newContent = preg_replace_callback(
    '/public static function form\(Schema \$schema\): Schema\s*\{.*?(?=\s*public static function table)/s',
    function ($matches) {
        return <<<'EOT'
public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Tabs::make('JobPostingTabs')
                    ->tabs([
                        \Filament\Forms\Components\Tabs\Tab::make('البيانات الأساسية للوظيفة')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('المسمى الوظيفي')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('department_id')
                                    ->label('القسم')
                                    ->relationship('department', 'name')
                                    ->nullable(),
                                Forms\Components\Select::make('location_id')
                                    ->label('مقر العمل')
                                    ->relationship('location', 'name')
                                    ->nullable(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('حالة الوظيفة (نشطة / مغلقة)')
                                    ->default(true),
                                Forms\Components\RichEditor::make('description')
                                    ->label('الوصف الوظيفي')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        \Filament\Forms\Components\Tabs\Tab::make('تخصيص الحقول الافتراضية')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                \Filament\Forms\Components\Fieldset::make('الحقول الأساسية لنموذج التقديم')
                                    ->schema([
                                        Forms\Components\Toggle::make('primary_fields_config.show_current_job_title')
                                            ->label('حقل "المسمى الوظيفي الحالي"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_current_company')
                                            ->label('حقل "جهة العمل الحالية"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_years_of_experience')
                                            ->label('حقل "سنوات الخبرة"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_expected_salary')
                                            ->label('حقل "الراتب المتوقع"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_seniority_level')
                                            ->label('حقل "المستوى المهني"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_work_preference')
                                            ->label('حقل "نظام العمل المفضل"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_availability')
                                            ->label('حقل "متى يمكنك البدء؟"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_resume')
                                            ->label('طلب إرفاق "السيرة الذاتية" (إجباري)')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_general_notes')
                                            ->label('حقل "ملاحظات إضافية"')->default(true),
                                    ])->columns(3),
                            ]),

                        \Filament\Forms\Components\Tabs\Tab::make('بناء أسئلة إضافية (Form Builder)')
                            ->icon('heroicon-o-squares-plus')
                            ->schema([
                                Builder::make('form_schema')
                                    ->label('الحقول الإضافية المخصصة')
                                    ->blocks([
                                        Block::make('text')
                                            ->label('إجابة قصيرة (نص)')
                                            ->icon('heroicon-m-bars-2')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('عنوان السؤال (يظهر للمتقدم)')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (?string $state, \Filament\Schemas\Components\Utilities\Set $set) => $set('name', \Illuminate\Support\Str::slug((string) $state, '_'))),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('المعرف البرمجي')
                                                    ->required()
                                                    ->regex('/^[a-z0-9_]+$/')
                                                    ->helperText('يتم توليده تلقائياً بناءً على السؤال (حروف إنجليزية وشرطة سفلية فقط).'),
                                                Forms\Components\Toggle::make('is_required')->label('إجباري؟')->default(false),
                                            ]),
                                        Block::make('textarea')
                                            ->label('إجابة طويلة')
                                            ->icon('heroicon-m-bars-3-bottom-left')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('عنوان السؤال')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (?string $state, \Filament\Schemas\Components\Utilities\Set $set) => $set('name', \Illuminate\Support\Str::slug((string) $state, '_'))),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('المعرف البرمجي')
                                                    ->required()
                                                    ->regex('/^[a-z0-9_]+$/'),
                                                Forms\Components\Toggle::make('is_required')->label('إجباري؟')->default(false),
                                            ]),
                                        Block::make('select')
                                            ->label('قائمة منسدلة')
                                            ->icon('heroicon-m-chevron-up-down')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('عنوان السؤال')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (?string $state, \Filament\Schemas\Components\Utilities\Set $set) => $set('name', \Illuminate\Support\Str::slug((string) $state, '_'))),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('المعرف البرمجي')
                                                    ->required()
                                                    ->regex('/^[a-z0-9_]+$/'),
                                                Forms\Components\TagsInput::make('options')->label('الخيارات المتاحة')->required(),
                                                Forms\Components\Toggle::make('is_multiple')->label('متعدد الاختيار؟ (Multi-select)')->default(false),
                                                Forms\Components\Toggle::make('is_required')->label('إجباري؟')->default(false),
                                            ]),
                                        Block::make('file')
                                            ->label('رفع ملف إضافي (غير السيرة الذاتية)')
                                            ->icon('heroicon-m-document-arrow-up')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('عنوان السؤال')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (?string $state, \Filament\Schemas\Components\Utilities\Set $set) => $set('name', \Illuminate\Support\Str::slug((string) $state, '_'))),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('المعرف البرمجي')
                                                    ->required()
                                                    ->regex('/^[a-z0-9_]+$/'),
                                                Forms\Components\Select::make('accepted_file_types')
                                                    ->label('أنواع الملفات المقبولة')
                                                    ->options([
                                                        'application/pdf' => 'PDF فقط',
                                                        'image/*' => 'صور فقط',
                                                        'application/pdf,image/*' => 'PDF أو صور',
                                                    ])
                                                    ->default('application/pdf,image/*'),
                                                Forms\Components\Toggle::make('is_required')->label('إجباري؟')->default(false),
                                            ]),
                                        Block::make('checkbox')
                                            ->label('اختيار (نعم/لا)')
                                            ->icon('heroicon-m-check-circle')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('عنوان السؤال')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (?string $state, \Filament\Schemas\Components\Utilities\Set $set) => $set('name', \Illuminate\Support\Str::slug((string) $state, '_'))),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('المعرف البرمجي')
                                                    ->required()
                                                    ->regex('/^[a-z0-9_]+$/'),
                                                Forms\Components\Toggle::make('is_required')->label('إجباري؟')->default(false),
                                            ]),
                                        Block::make('date')
                                            ->label('منتقي التاريخ (Date Picker)')
                                            ->icon('heroicon-m-calendar')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('عنوان السؤال')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (?string $state, \Filament\Schemas\Components\Utilities\Set $set) => $set('name', \Illuminate\Support\Str::slug((string) $state, '_'))),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('المعرف البرمجي')
                                                    ->required()
                                                    ->regex('/^[a-z0-9_]+$/'),
                                                Forms\Components\Toggle::make('is_required')->label('إجباري؟')->default(false),
                                            ]),
                                        Block::make('rating')
                                            ->label('تقييم (Scale 1-5)')
                                            ->icon('heroicon-m-star')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('عنوان السؤال')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (?string $state, \Filament\Schemas\Components\Utilities\Set $set) => $set('name', \Illuminate\Support\Str::slug((string) $state, '_'))),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('المعرف البرمجي')
                                                    ->required()
                                                    ->regex('/^[a-z0-9_]+$/'),
                                                Forms\Components\Toggle::make('is_required')->label('إجباري؟')->default(false),
                                            ]),
                                    ])
                                    ->columnSpanFull()
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
EOT;
    },
    $content
);

file_put_contents('app/Filament/Admin/Resources/JobPostingResource.php', $newContent);
echo "DONE";
