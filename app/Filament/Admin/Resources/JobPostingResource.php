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
        return 'التوظيف';
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
                                    ->helperText('اتركه فارغاً لاستخدام اللون الافتراضي')
                                    ->nullable(),
                                \Filament\Forms\Components\ToggleButtons::make('form_layout')
                                    ->label('شكل نموذج التقديم (Layout)')
                                    ->options([
                                        'single_page' => 'صفحة واحدة طويلة',
                                        'wizard' => 'نظام الخطوات المتعددة (Wizard)'
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
                                    ->inline(),
                                \Filament\Forms\Components\RichEditor::make('success_message')
                                    ->label('رسالة النجاح المخصصة (تظهر بعد التقديم)')
                                    ->helperText('مثال: شكراً لك، سيقوم فريقنا بمراجعة أعمالك. (اتركه فارغاً للرسالة الافتراضية)')
                                    ->columnSpanFull(),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make('تخصيص الحقول الافتراضية')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->components([
                                \Filament\Schemas\Components\Fieldset::make('الحقول الأساسية لنموذج التقديم')
                                    ->components([
                                                                                Forms\Components\Toggle::make('primary_fields_config.show_full_name')
                                            ->label('حقل "الاسم الكامل" (إجباري)')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_email')
                                            ->label('حقل "البريد الإلكتروني" (إجباري)')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_phone')
                                            ->label('حقل "رقم الجوال" (إجباري)')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_linkedin')
                                            ->label('حقل "لينكد إن"')->default(true),
                                        Forms\Components\Toggle::make('primary_fields_config.show_portfolio')
                                            ->label('حقل "معرض الأعمال"')->default(true),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit' => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}
