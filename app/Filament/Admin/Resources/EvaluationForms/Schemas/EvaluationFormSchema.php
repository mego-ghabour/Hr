<?php

namespace App\Filament\Admin\Resources\EvaluationForms\Schemas;

use App\Models\Department;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EvaluationFormSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        // ==========================================
                        // العمود الرئيسي (2 من 3): الهوية وبنود التقييم
                        // ==========================================
                        Group::make([
                            Section::make('البيانات الأساسية ونمط التقييم')
                                ->description('حدد اسم النموذج والغرض منه، مع إمكانية إضافة إرشادات تظهر للمُقيّم.')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('عنوان النموذج / الاستبيان')
                                        ->placeholder('مثال: تقييم أداء الوكلاء الشهري (Customer Support Agents)')
                                        ->prefixIcon('heroicon-o-pencil-square')
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
                                        ->rows(2)
                                        ->columnSpanFull(),
                                ]),

                            Section::make('معايير وبنود التقييم (Evaluation Criteria)')
                                ->description('أضف المعايير ومقياس الدرجات لكل بند، وسيتم احتساب متوسط الدرجة النهائية تلقائياً.')
                                ->icon('heroicon-o-queue-list')
                                ->schema([
                                    Repeater::make('questions')
                                        ->label('قائمة بنود ومعايير التقييم')
                                        ->addActionLabel('+ إضافة معيار تقييم جديد')
                                        ->reorderable()
                                        ->collapsible()
                                        ->cloneable()
                                        ->itemLabel(fn (array $state): ?string => !empty($state['label']) ? $state['label'] : 'معيار تقييم جديد')
                                        ->schema([
                                            Grid::make(['default' => 1, 'sm' => 12])
                                                ->schema([
                                                    TextInput::make('label')
                                                        ->label('نص المعيار أو السؤال')
                                                        ->placeholder('مثال: الإنتاجية وسرعة إنجاز المهام المطلوبة...')
                                                        ->required()
                                                        ->columnSpan(['default' => 12, 'sm' => 6]),

                                                    Select::make('type')
                                                        ->label('مقياس الدرجات')
                                                        ->options([
                                                            'rating_5' => '⭐⭐⭐⭐⭐ نجوم (1 إلى 5)',
                                                            'rating_10' => '🎯 درجات (1 إلى 10)',
                                                            'boolean' => '✓ نعم / لا',
                                                            'text' => '📝 ملاحظات نصية',
                                                        ])
                                                        ->default('rating_5')
                                                        ->required()
                                                        ->native(false)
                                                        ->columnSpan(['default' => 12, 'sm' => 4]),

                                                    Toggle::make('required')
                                                        ->label('إلزامي')
                                                        ->default(true)
                                                        ->inline(false)
                                                        ->columnSpan(['default' => 12, 'sm' => 2]),
                                                ]),
                                        ])
                                        ->default([
                                            [
                                                'label' => 'الإنتاجية وسرعة إنجاز المهام الموكلة إليه',
                                                'type' => 'rating_5',
                                                'required' => true,
                                                'weight' => 1,
                                            ],
                                            [
                                                'label' => 'دقة وجودة العمل والالتزام بالمعايير والسياسات',
                                                'type' => 'rating_5',
                                                'required' => true,
                                                'weight' => 1,
                                            ],
                                            [
                                                'label' => 'الانضباط ومواعيد الحضور والانصراف وساعات العمل',
                                                'type' => 'rating_5',
                                                'required' => true,
                                                'weight' => 1,
                                            ],
                                            [
                                                'label' => 'مهارات التواصل الفعال والتعاون الإيجابي مع الفريق',
                                                'type' => 'rating_5',
                                                'required' => true,
                                                'weight' => 1,
                                            ],
                                        ])
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(['default' => 1, 'lg' => 2]),

                        // ==========================================
                        // العمود الجانبي (1 من 3): النطاق والنشر
                        // ==========================================
                        Group::make([
                            Section::make('نطاق وتطبيق النموذج')
                                ->description('حدد من هم الموظفون المستهدفون بهذا النموذج.')
                                ->icon('heroicon-o-user-group')
                                ->schema([
                                    ToggleButtons::make('target_type')
                                        ->label('تطبيق النموذج على')
                                        ->options([
                                            'all' => 'كافة الموظفين (موحد)',
                                            'department' => 'قسم محدد',
                                            'specific_role' => 'مسمى وظيفي',
                                        ])
                                        ->colors([
                                            'all' => 'primary',
                                            'department' => 'warning',
                                            'specific_role' => 'info',
                                        ])
                                        ->default('all')
                                        ->required()
                                        ->live()
                                        ->columnSpanFull(),

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

                            Section::make('حالة النشر والتفعيل')
                                ->icon('heroicon-o-check-badge')
                                ->schema([
                                    Toggle::make('is_active')
                                        ->label('تفعيل النموذج فوراً')
                                        ->helperText('عند التفعيل، يظهر النموذج تلقائياً في خيارات التقييم وبوابة الموظفين.')
                                        ->default(true),
                                ]),

                            Section::make('💡 احتساب النتائج الذكي')
                                ->icon('heroicon-o-light-bulb')
                                ->schema([
                                    Placeholder::make('info_box')
                                        ->label('')
                                        ->content('يقوم النظام تلقائياً باحتساب متوسط الدرجة لجميع الأسئلة والتقييمات من 5.0 نجوم ⭐ فور تقديم التقييم، مع توفير خانات لتحليل نقاط القوة وخطة العمل التطويرية.'),
                                ]),
                        ])->columnSpan(['default' => 1, 'lg' => 1]),
                    ]),
            ]);
    }
}
