<?php

namespace App\Filament\Admin\Resources\Evaluations\Schemas;

use App\Models\Employee;
use App\Models\EvaluationForm;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EvaluationSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات التقييم والشخص الخاضع للتقييم')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('evaluation_form_id')
                                ->label('نموذج التقييم المستخدم')
                                ->options(fn () => EvaluationForm::where('is_active', true)->pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->native(false),

                            Select::make('evaluated_employee_id')
                                ->label('الموظف / الوكيل الخاضع للتقييم')
                                ->options(fn () => Employee::query()
                                    ->get()
                                    ->mapWithKeys(fn ($emp) => [$emp->id => "{$emp->full_name} ({$emp->job_title}) - " . ($emp->department?->name ?? 'بدون قسم')])
                                )
                                ->searchable()
                                ->preload()
                                ->required()
                                ->native(false),

                            TextInput::make('period')
                                ->label('الفترة التقييمية')
                                ->placeholder('مثال: أكتوبر 2026، الربع الرابع')
                                ->default(date('F Y'))
                                ->required(),

                            Toggle::make('is_anonymous')
                                ->label('تقييم سري (إخفاء هوية المُقيّم) 🔒')
                                ->helperText('في حال تفعيل هذا الخيار، لن يظهر اسمك في التقرير للموظف أو المدير الخاضع للتقييم.')
                                ->default(false)
                                ->columnSpan(2),

                            Select::make('status')
                                ->label('حالة التقييم')
                                ->options([
                                    'draft' => 'مسودة (Draft)',
                                    'submitted' => 'تم التقديم (Submitted)',
                                    'reviewed' => 'معتمد ومراجع (Reviewed)',
                                ])
                                ->default('submitted')
                                ->required()
                                ->native(false),
                        ]),
                    ]),

                Section::make('معايير وأسئلة التقييم (Evaluation Criteria)')
                    ->description('تظهر المعايير أدناه تلقائياً بناءً على نموذج التقييم المختار أعلاه.')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema(function (Get $get): array {
                        $formId = $get('evaluation_form_id');
                        if (!$formId) {
                            return [
                                Placeholder::make('hint')
                                    ->label('')
                                    ->content('← يرجى اختيار نموذج التقييم من القسم أعلاه لعرض بنود ومعايير التقييم الخاصة به.'),
                            ];
                        }

                        $form = EvaluationForm::find($formId);
                        if (!$form || empty($form->questions)) {
                            return [
                                Placeholder::make('hint')
                                    ->label('')
                                    ->content('لا توجد أسئلة أو معايير مضافة في هذا النموذج حتى الآن.'),
                            ];
                        }

                        $components = [];
                        foreach ($form->questions as $index => $q) {
                            $key = "answers.q_{$index}";
                            $label = ($index + 1) . '. ' . ($q['label'] ?? ('معيار رقم ' . ($index + 1)));
                            $type = $q['type'] ?? 'rating_5';
                            $required = $q['required'] ?? true;

                            if ($type === 'rating_5') {
                                $components[] = Select::make($key)
                                    ->label($label)
                                    ->options([
                                        5 => '⭐⭐⭐⭐⭐ ممتاز (5 من 5)',
                                        4 => '⭐⭐⭐⭐ جيد جداً (4 من 5)',
                                        3 => '⭐⭐⭐ جيد (3 من 5)',
                                        2 => '⭐⭐ مقبول (2 من 5)',
                                        1 => '⭐ ضعيف / يحتاج تطوير (1 من 5)',
                                    ])
                                    ->required($required)
                                    ->native(false);
                            } elseif ($type === 'rating_10') {
                                $components[] = Select::make($key)
                                    ->label($label)
                                    ->options([
                                        10 => '10 / 10 - استثنائي',
                                        9 => '9 / 10 - ممتاز مرتفع',
                                        8 => '8 / 10 - جيد جداً مرتفع',
                                        7 => '7 / 10 - جيد جداً',
                                        6 => '6 / 10 - جيد',
                                        5 => '5 / 10 - متوسط',
                                        4 => '4 / 10 - دون المتوسط',
                                        3 => '3 / 10 - ضعيف',
                                        2 => '2 / 10 - ضعيف جداً',
                                        1 => '1 / 10 - غير مقبول',
                                    ])
                                    ->required($required)
                                    ->native(false);
                            } elseif ($type === 'boolean') {
                                $components[] = Select::make($key)
                                    ->label($label)
                                    ->options([
                                        1 => 'نعم / محقق بالكامل',
                                        0 => 'لا / غير محقق',
                                    ])
                                    ->required($required)
                                    ->native(false);
                            } else {
                                $components[] = Textarea::make($key)
                                    ->label($label)
                                    ->rows(2)
                                    ->placeholder('اكتب الإجابة أو الملاحظات...')
                                    ->required($required);
                            }
                        }

                        return $components;
                    })
                    ->columns(2),

                Section::make('التحليل النوعي وخطة التطوير (Action Plan & Feedback)')
                    ->icon('heroicon-o-light-bulb')
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('strengths')
                                ->label('أبرز نقاط القوة والإنجازات')
                                ->placeholder('ما الذي تميز به الموظف/المدير خلال هذه الفترة؟')
                                ->rows(3),

                            Textarea::make('areas_for_improvement')
                                ->label('نقاط ومجالات التحسين')
                                ->placeholder('ما هي النقاط التي تحتاج إلى تطوير أو تدريب؟')
                                ->rows(3),

                            Textarea::make('action_plan')
                                ->label('خطة العمل والتطوير المقترحة (Action Plan)')
                                ->placeholder('خطوات واضحة لتحسين الأداء خلال الشهر / الربع القادم...')
                                ->rows(3)
                                ->columnSpanFull(),

                            Textarea::make('notes')
                                ->label('ملاحظات إضافية عامة')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
