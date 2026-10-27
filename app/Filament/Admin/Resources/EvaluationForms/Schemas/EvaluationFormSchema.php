<?php

namespace App\Filament\Admin\Resources\EvaluationForms\Schemas;

use App\Models\Department;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EvaluationFormSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات النموذج الأساسية والهدف منه')
                    ->description('حدد عنوان النموذج، نوع التقييم، والفئة المستهدفة من الموظفين.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('عنوان النموذج / الاستبيان')
                                ->placeholder('مثال: تقييم أداء الوكلاء الشهري، أو تقييم المديرين (Upward Feedback)')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2),

                            Select::make('type')
                                ->label('نوع التقييم')
                                ->options([
                                    'manager_to_employee' => 'تقييم المدير للموظف / الوكيل (Agent Evaluation)',
                                    'employee_to_manager' => 'تقييم الموظف للمدير (Upward Feedback)',
                                    'general_survey' => 'استبيان / قياس رضا الموظفين (General Survey)',
                                ])
                                ->required()
                                ->default('manager_to_employee')
                                ->native(false),

                            Select::make('target_type')
                                ->label('الفئة المستهدفة (نطاق النموذج)')
                                ->options([
                                    'all' => 'كافة الموظفين (نموذج موحد للجميع)',
                                    'department' => 'قسم مخصص فقط (Department)',
                                    'specific_role' => 'مسمى / دور وظيفي محدد (Role/Job Title)',
                                ])
                                ->required()
                                ->default('all')
                                ->live()
                                ->native(false),

                            Select::make('department_id')
                                ->label('اختر القسم المستهدف')
                                ->options(fn () => Department::query()->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->visible(fn (Get $get) => $get('target_type') === 'department')
                                ->required(fn (Get $get) => $get('target_type') === 'department')
                                ->native(false),

                            TextInput::make('target_role')
                                ->label('المسمى الوظيفي المستهدف')
                                ->placeholder('مثال: Customer Service Agent, Sales Rep')
                                ->visible(fn (Get $get) => $get('target_type') === 'specific_role')
                                ->required(fn (Get $get) => $get('target_type') === 'specific_role'),

                            Textarea::make('description')
                                ->label('إرشادات وتوجيهات تعبئة النموذج')
                                ->placeholder('اكتب هنا أي تعليمات إرشادية تظهر للشخص الذي سيقوم بتعبئة التقييم...')
                                ->rows(3)
                                ->columnSpanFull(),

                            Toggle::make('is_active')
                                ->label('تفعيل النموذج (يظهر في خيارات التقييم)')
                                ->default(true)
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('معايير وأسئلة التقييم (Form Criteria & Questions)')
                    ->description('أضف المعايير والأسئلة التي سيتم تقييم الشخص بناءً عليها.')
                    ->icon('heroicon-o-list-bullet')
                    ->schema([
                        Repeater::make('questions')
                            ->label('قائمة الأسئلة والمعايير')
                            ->addActionLabel('إضافة معيار / سؤال جديد')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'معيار جديد')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('label')
                                        ->label('نص المعيار أو السؤال')
                                        ->placeholder('مثال: جودة ودقة العمل المنجز')
                                        ->required()
                                        ->columnSpan(2),

                                    Select::make('type')
                                        ->label('نوع الإجابة / المقياس')
                                        ->options([
                                            'rating_5' => 'تقييم بالنجوم / درجات (من 1 إلى 5)',
                                            'rating_10' => 'تقييم بالدرجات (من 1 إلى 10)',
                                            'text' => 'إجابة نصية / ملاحظات',
                                            'boolean' => 'نعم / لا',
                                        ])
                                        ->default('rating_5')
                                        ->required()
                                        ->native(false),

                                    Toggle::make('required')
                                        ->label('إلزامي الإجابة')
                                        ->default(true),

                                    TextInput::make('weight')
                                        ->label('الوزن النسبي')
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1)
                                        ->maxValue(10),
                                ]),
                            ])
                            ->default([
                                [
                                    'label' => 'الإنتاجية وسرعة إنجاز المهام المطلوبة',
                                    'type' => 'rating_5',
                                    'required' => true,
                                    'weight' => 1,
                                ],
                                [
                                    'label' => 'دقة وجودة العمل والالتزام بالمعايير',
                                    'type' => 'rating_5',
                                    'required' => true,
                                    'weight' => 1,
                                ],
                                [
                                    'label' => 'الالتزام بمواعيد العمل والحضور والانصراف',
                                    'type' => 'rating_5',
                                    'required' => true,
                                    'weight' => 1,
                                ],
                                [
                                    'label' => 'التعاون مع الزملاء وروح الفريق الإيجابية',
                                    'type' => 'rating_5',
                                    'required' => true,
                                    'weight' => 1,
                                ],
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
