<?php

namespace App\Filament\Admin\Resources\EvaluationForms\Schemas;

use App\Models\Department;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EvaluationFormSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('EvaluationFormTabs')
                    ->tabs([
                        // ==========================================
                        // التبويب الأول: هوية النموذج وبياناته الأساسية
                        // ==========================================
                        Tab::make('البيانات الأساسية ونطاق النموذج')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('هوية النموذج ونمط التقييم')
                                    ->description('حدد عنوان النموذج والغرض منه، مع إمكانية إضافة إرشادات تظهر للمُقيّم أعلى الصفحة.')
                                    ->icon('heroicon-o-pencil-square')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('عنوان النموذج / الاستبيان')
                                            ->placeholder('مثال: تقييم أداء الوكلاء الشهري (Agents Monthly Appraisal)')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        ToggleButtons::make('type')
                                            ->label('نوع ونمط التقييم')
                                            ->options([
                                                'manager_to_employee' => 'تقييم المدير للموظف (Agent)',
                                                'employee_to_manager' => 'تقييم الموظف للمدير (Upward Feedback)',
                                                'general_survey' => 'استبيان قياس رضا عام',
                                            ])
                                            ->icons([
                                                'manager_to_employee' => 'heroicon-o-user-group',
                                                'employee_to_manager' => 'heroicon-o-shield-check',
                                                'general_survey' => 'heroicon-o-chart-bar',
                                            ])
                                            ->colors([
                                                'manager_to_employee' => 'info',
                                                'employee_to_manager' => 'warning',
                                                'general_survey' => 'success',
                                            ])
                                            ->default('manager_to_employee')
                                            ->required()
                                            ->inline()
                                            ->columnSpanFull(),

                                        Textarea::make('description')
                                            ->label('إرشادات وتوجيهات تعبئة النموذج (تظهر أعلى صفحة التقييم)')
                                            ->placeholder('اكتب هنا توجيهات للموظف أو المدير توضح معايير وطريقة التقييم والهدف منه...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('نطاق التطبيق وحالة النشر')
                                    ->description('حدد الفئة المستهدفة بهذا النموذج من الموظفين وحالة نشره بالنظام.')
                                    ->icon('heroicon-o-user-group')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Select::make('target_type')
                                                ->label('تطبيق النموذج على')
                                                ->options([
                                                    'all' => 'كافة الموظفين بالشركة (نموذج موحد)',
                                                    'department' => 'قسم محدد',
                                                    'specific_role' => 'مسمى وظيفي محدد',
                                                ])
                                                ->default('all')
                                                ->required()
                                                ->live()
                                                ->native(false),

                                            Toggle::make('is_active')
                                                ->label('تفعيل النموذج فوراً')
                                                ->helperText('عند التفعيل، يتاح النموذج فوراً في سجلات التقييم وبوابة الموظفين.')
                                                ->default(true)
                                                ->inline(false),

                                            Select::make('department_id')
                                                ->label('اختر القسم المستهدف')
                                                ->options(fn () => Department::pluck('name', 'id'))
                                                ->searchable()
                                                ->preload()
                                                ->visible(fn (Get $get) => $get('target_type') === 'department')
                                                ->required(fn (Get $get) => $get('target_type') === 'department')
                                                ->native(false)
                                                ->columnSpanFull(),

                                            TextInput::make('target_role')
                                                ->label('المسمى الوظيفي المستهدف')
                                                ->placeholder('مثال: Customer Service Agent')
                                                ->visible(fn (Get $get) => $get('target_type') === 'specific_role')
                                                ->required(fn (Get $get) => $get('target_type') === 'specific_role')
                                                ->columnSpanFull(),
                                        ]),
                                    ]),
                            ]),

                        // ==========================================
                        // التبويب الثاني: معايير وبنود التقييم
                        // ==========================================
                        Tab::make('معايير وبنود التقييم (Criteria)')
                            ->icon('heroicon-o-queue-list')
                            ->schema([
                                Section::make('قائمة بنود التقييم ومقاييس الدرجات')
                                    ->description('أضف المعايير ومقياس الدرجات لكل بند، وسيتم احتساب متوسط الدرجة النهائية آلياً من 5 نجوم ⭐.')
                                    ->schema([
                                        Repeater::make('questions')
                                            ->label('بنود ومعايير التقييم')
                                            ->addActionLabel('+ إضافة معيار تقييم جديد')
                                            ->reorderable()
                                            ->collapsible()
                                            ->cloneable()
                                            ->itemLabel(fn (array $state): ?string => !empty($state['label']) ? $state['label'] : 'معيار تقييم جديد')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->label('نص المعيار أو السؤال التقييمي')
                                                    ->placeholder('مثال: الإنتاجية وسرعة إنجاز المهام الموكلة إليه بدقة...')
                                                    ->required()
                                                    ->columnSpanFull(),

                                                Grid::make(2)->schema([
                                                    Select::make('type')
                                                        ->label('طريقة احتساب الدرجة / مقياس التقييم')
                                                        ->options([
                                                            'rating_5' => '⭐⭐⭐⭐⭐ تقييم نجوم (1 إلى 5)',
                                                            'rating_10' => '🎯 مقياس درجات (1 إلى 10)',
                                                            'boolean' => '✓ نعم / لا (محقق / غير محقق)',
                                                            'text' => '📝 ملاحظات ومقترحات نصية',
                                                        ])
                                                        ->default('rating_5')
                                                        ->required()
                                                        ->native(false),

                                                    Toggle::make('required')
                                                        ->label('بند إلزامي (يتطلب إجابة لإتمام التقييم)')
                                                        ->default(true)
                                                        ->inline(false),
                                                ]),
                                            ])
                                            ->default([
                                                [
                                                    'label' => 'الإنتاجية وسرعة إنجاز المهام الموكلة إليه',
                                                    'type' => 'rating_5',
                                                    'required' => true,
                                                ],
                                                [
                                                    'label' => 'دقة وجودة العمل والالتزام بالمعايير والسياسات الداخلية',
                                                    'type' => 'rating_5',
                                                    'required' => true,
                                                ],
                                                [
                                                    'label' => 'الانضباط ومواعيد الحضور والانصراف وساعات العمل الرسمية',
                                                    'type' => 'rating_5',
                                                    'required' => true,
                                                ],
                                                [
                                                    'label' => 'مهارات التواصل الفعال والتعاون الإيجابي مع فريق العمل',
                                                    'type' => 'rating_5',
                                                    'required' => true,
                                                ],
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('💡 آلية احتساب النتيجة الذكية')
                                    ->icon('heroicon-o-light-bulb')
                                    ->schema([
                                        Placeholder::make('score_info')
                                            ->label('')
                                            ->content('⭐ يقوم النظام تلقائياً باحتساب متوسط الدرجة لجميع الأسئلة والتقييمات من 5.0 نجوم فور تقديم التقييم، مع توفير خانات نوعية لتحليل نقاط القوة ومجالات التحسين وخطة العمل التطويرية (Action Plan).'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
