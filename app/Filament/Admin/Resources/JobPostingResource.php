<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JobPostingResource\Pages;
use App\Models\JobPosting;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-briefcase';
    }
    
    public static function getModelLabel(): string
    {
        return 'وظيفة';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'الوظائف ونماذج التقديم';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'إدارة التوظيف واستقطاب المواهب';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_active', true)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Tabs::make('JobPostingTabs')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('البيانات الأساسية للوظيفة')
                            ->icon('heroicon-o-information-circle')
                            ->components([
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

                                                \Filament\Schemas\Components\Tabs\Tab::make('الهوية والمظهر (Branding)')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                \Filament\Forms\Components\ColorPicker::make('brand_color')
                                    ->label('اللون الأساسي لنموذج التقديم (Primary Color)')
                                    ->helperText('اتركه فارغاً لاستخدام اللون الافتراضي للشركة (#0d9488)')
                                    ->nullable(),
                                \Filament\Forms\Components\ToggleButtons::make('form_layout')
                                    ->label('شكل وتصميم نموذج التقديم (Layout)')
                                    ->options([
                                        'single_page' => 'صفحة واحدة طويلة (Single Page)',
                                        'wizard' => 'نظام الخطوات المتعددة (Interactive Wizard)'
                                    ])
                                    ->icons([
                                        'single_page' => 'heroicon-m-document',
                                        'wizard' => 'heroicon-m-view-columns',
                                    ])
                                    ->colors([
                                        'single_page' => 'info',
                                        'wizard' => 'success',
                                    ])
                                    ->default('single_page')
                                    ->helperText('عند اختيار (Wizard)، سيتم تقسيم صفحة التقديم الخارجية تلقائياً إلى خطوات تفاعلية متسلسلة (بيانات شخصية -> خبرات -> أسئلة ومرفقات -> مراجعة وتأكيد) مع شريط تقدم وتحقق فوري.')
                                    ->inline(),
                                \Filament\Forms\Components\RichEditor::make('success_message')
                                    ->label('رسالة النجاح المخصصة (تظهر بعد التقديم)')
                                    ->helperText('مثال: شكراً لك، سيقوم فريقنا بمراجعة أعمالك. (اتركه فارغاً للرسالة الافتراضية)')
                                    ->columnSpanFull(),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make('تخصيص الحقول الأساسية لنموذج التقديم')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->components([
                                \Filament\Schemas\Components\Section::make('البيانات الشخصية والتعريفية (إظهار / إجباري)')
                                    ->description('حدد الحقول المطلوب ظهورها للمرشح وما إذا كان ملء الحقل إجبارياً أو اختيارياً.')
                                    ->schema([
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_full_name')
                                                ->label('إظهار حقل "الاسم الكامل"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_full_name')
                                                ->label('إجباري (مطلوب)')
                                                ->default(true),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_email')
                                                ->label('إظهار حقل "البريد الإلكتروني"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_email')
                                                ->label('إجباري (مطلوب)')
                                                ->default(true),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_phone')
                                                ->label('إظهار حقل "رقم الجوال"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_phone')
                                                ->label('إجباري (مطلوب)')
                                                ->default(true),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_national_id')
                                                ->label('إظهار حقل "رقم الهوية الوطنية / الإقامة"')
                                                ->default(false),
                                            Forms\Components\Toggle::make('primary_fields_config.required_national_id')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_city')
                                                ->label('إظهار حقل "المدينة ومحل الإقامة"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_city')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_birth_date')
                                                ->label('إظهار حقل "تاريخ الميلاد"')
                                                ->default(false),
                                            Forms\Components\Toggle::make('primary_fields_config.required_birth_date')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_gender')
                                                ->label('إظهار حقل "الجنس / النوع"')
                                                ->default(false),
                                            Forms\Components\Toggle::make('primary_fields_config.required_gender')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                    ])->columns(2),

                                \Filament\Schemas\Components\Section::make('المؤهلات والمسيرة المهنية (إظهار / إجباري)')
                                    ->schema([
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_resume')
                                                ->label('إظهار رفع "السيرة الذاتية (CV)"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_resume')
                                                ->label('إجباري (مطلوب)')
                                                ->default(true),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_current_job_title')
                                                ->label('إظهار "المسمى الوظيفي الحالي"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_current_job_title')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_current_company')
                                                ->label('إظهار "جهة العمل الحالية"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_current_company')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_years_of_experience')
                                                ->label('إظهار "سنوات الخبرة"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_years_of_experience')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_expected_salary')
                                                ->label('إظهار "الراتب المتوقع"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_expected_salary')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_seniority_level')
                                                ->label('إظهار "المستوى المهني"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_seniority_level')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_work_preference')
                                                ->label('إظهار "نظام العمل المفضل"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_work_preference')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_availability')
                                                ->label('إظهار "موعد بدء العمل المتاح"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_availability')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                    ])->columns(2),

                                \Filament\Schemas\Components\Section::make('الروابط الخارجية والملاحظات (إظهار / إجباري)')
                                    ->schema([
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_linkedin')
                                                ->label('إظهار حقل "رابط لينكد إن"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_linkedin')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_portfolio')
                                                ->label('إظهار حقل "معرض الأعمال / الموقع"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_portfolio')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                        \Filament\Schemas\Components\Grid::make(2)->schema([
                                            Forms\Components\Toggle::make('primary_fields_config.show_general_notes')
                                                ->label('إظهار حقل "ملاحظات إضافية"')
                                                ->default(true),
                                            Forms\Components\Toggle::make('primary_fields_config.required_general_notes')
                                                ->label('إجباري (مطلوب)')
                                                ->default(false),
                                        ]),
                                    ])->columns(2),
                            ]),

                        \Filament\Schemas\Components\Tabs\Tab::make('بناء أسئلة إضافية (Form Builder)')
                            ->icon('heroicon-o-squares-plus')
                            ->components([
                                Builder::make('form_schema')
                                    ->label('الحقول الإضافية المخصصة')
                                    ->blocks([
                                        Block::make('text')
                                            ->label('إجابة قصيرة (نص)')
                                            ->icon('heroicon-m-bars-2')
                                            ->components([
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
                                            ->components([
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
                                            ->components([
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
                                            ->components([
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
                                            ->components([
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
                                            ->components([
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
                                            ->components([
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('المسمى الوظيفي')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('القسم')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('النشاط')
                    ->boolean(),
                Tables\Columns\TextColumn::make('talents_count')
                    ->counts('talents')
                    ->label('عدد المتقدمين')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('القسم')
                    ->relationship('department', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('حالة الوظيفة')
                    ->trueLabel('نشطة ومتاحة للتقديم')
                    ->falseLabel('مغلقة'),
            ])
            ->actions([
                \Filament\Actions\Action::make('view_applicants')
                    ->label('المتقدمين')
                    ->icon('heroicon-o-users')
                    ->color('primary')
                    ->url(fn (\App\Models\JobPosting $record): string => url('/admin/talent?tableFilters[job_posting_id][value]=' . $record->id)),
                \Filament\Actions\Action::make('copy_link')
                    ->label('نسخ الرابط')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('gray')
                    ->action(function (\App\Models\JobPosting $record) {
                        \Filament\Notifications\Notification::make()
                            ->title('رابط التقديم للوظيفة')
                            ->body(url('/jobs/' . $record->id . '/apply'))
                            ->info()
                            ->send();
                    })
                    ->extraAttributes(fn (\App\Models\JobPosting $record) => [
                        'x-on:click' => new \Illuminate\Support\HtmlString('window.navigator.clipboard.writeText("' . url('/jobs/' . $record->id . '/apply') . '"); $tooltip("تم نسخ رابط التقديم بنجاح!");'),
                    ]),
                \Filament\Actions\Action::make('preview')
                    ->label('معاينة')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (\App\Models\JobPosting $record): string => url('/jobs/' . $record->id . '/apply'))
                    ->openUrlInNewTab(),
                \Filament\Actions\ReplicateAction::make()
                    ->label('نسخ الوظيفة')
                    ->tooltip('إنشاء وظيفة جديدة بنفس نموذج التقديم')
                    ->color('success')
                    ->excludeAttributes(['is_active']),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['department', 'location'])
            ->withCount('talents');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit' => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}
