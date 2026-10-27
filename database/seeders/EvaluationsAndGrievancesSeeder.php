<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\EvaluationForm;
use App\Models\Grievance;
use App\Models\User;
use Illuminate\Database\Seeder;

class EvaluationsAndGrievancesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin?->id ?? 1;

        // 1. نموذج تقييم المدير للموظف / الوكيل (Agent Evaluation)
        $agentForm = EvaluationForm::firstOrCreate(
            ['title' => 'نموذج تقييم أداء الوكلاء والموظفين (Agent Performance Appraisal)'],
            [
                'type' => 'manager_to_employee',
                'target_type' => 'all',
                'description' => 'نموذج دوري رسمي لقياس أداء وإنتاجية الوكلاء والموظفين، وجودة العمل والالتزام الوظيفي.',
                'questions' => [
                    [
                        'id' => 'q_0',
                        'label' => 'الإنتاجية وسرعة إنجاز المهام / التذاكر الموكلة إليه',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_1',
                        'label' => 'دقة وجودة العمل والالتزام بالمعايير والسياسات المعتمدة',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_2',
                        'label' => 'الانضباط ومواعيد الحضور والانصراف والالتزام بساعات العمل',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_3',
                        'label' => 'مهارات التواصل الفعال والتعامل باحترافية مع العملاء والزملاء',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_4',
                        'label' => 'المرونة وتقبل التوجيه والملاحظات البناءة من الإدارة',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                ],
                'is_active' => true,
                'created_by' => $adminId,
            ]
        );

        // 2. نموذج تقييم الموظف للمدير المباشر (Upward 360 Feedback)
        $managerForm = EvaluationForm::firstOrCreate(
            ['title' => 'استبيان تقييم المديرين وقادة الفرق (Upward Feedback)'],
            [
                'type' => 'employee_to_manager',
                'target_type' => 'all',
                'description' => 'نموذج سري مخصص للموظفين لتقييم أسلوب القيادة والتوجيه للمدير المباشر بهدف تحسين بيئة العمل وتطوير الأداء الإداري.',
                'questions' => [
                    [
                        'id' => 'q_0',
                        'label' => 'العدالة والشفافية في توزيع المهام والمسؤوليات بين أعضاء الفريق',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_1',
                        'label' => 'وضوح التوجيهات وتحديد الأهداف المطلوبة من الموظف',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_2',
                        'label' => 'تقديم الدعم والتشجيع المستمر وتوفير الأدوات اللازمة للنجاح',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_3',
                        'label' => 'الاستماع الفعال لآراء الفريق وتقبل الاقتراحات برحابة صدر',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_4',
                        'label' => 'التعامل باحترام ومهنية عالية في معالجة الأخطاء والمشكلات',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                ],
                'is_active' => true,
                'created_by' => $adminId,
            ]
        );

        // 3. نموذج استبيان الرضا العام وبيئة العمل (General Work Environment Survey)
        $surveyForm = EvaluationForm::firstOrCreate(
            ['title' => 'استبيان قياس الرضا الوظيفي وبيئة العمل'],
            [
                'type' => 'general_survey',
                'target_type' => 'all',
                'description' => 'استبيان عام لقياس رضا الموظفين عن بيئة ومناخ العمل بالشركة والخدمات المقدمة.',
                'questions' => [
                    [
                        'id' => 'q_0',
                        'label' => 'مدى رضاك عن بيئة العمل المادية (المكان، الأجهزة، الأدوات)',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_1',
                        'label' => 'مدى شعورك بالتقدير المالي والمعنوي لجهودك في العمل',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                    [
                        'id' => 'q_2',
                        'label' => 'توازن الحياة المهنية والشخصية (Work-Life Balance)',
                        'type' => 'rating_5',
                        'required' => true,
                        'weight' => 1,
                    ],
                ],
                'is_active' => true,
                'created_by' => $adminId,
            ]
        );

        // إنشاء نموذج شكوى تجريبية للتأكد من عمل النظام
        $firstEmployee = Employee::first();
        if ($firstEmployee) {
            Grievance::firstOrCreate(
                ['title' => 'طلب صيانة أجهزة التكييف في صالة خدمة العملاء'],
                [
                    'user_id' => $firstEmployee->user_id ?? $adminId,
                    'employee_id' => $firstEmployee->id,
                    'is_anonymous' => false,
                    'type' => 'workplace',
                    'priority' => 'normal',
                    'content' => 'نرجو التكرم بعمل صيانة دورية لأجهزة التكييف في الصالة الرئيسية حيث أن درجة الحرارة تؤثر على راحة الفريق أثناء ساعات العمل.',
                    'against_manager' => false,
                    'status' => 'resolved',
                    'hr_response' => 'تم التنسيق مع فريق الصيانة العامة وتم عمل الصيانة اللازمة للأجهزة واختبار كفاءتها.',
                    'resolved_at' => now(),
                    'assigned_to' => $adminId,
                ]
            );

            // شكوى سرية تجريبية
            Grievance::firstOrCreate(
                ['title' => 'مقترح لتطوير نظام استراحة الغداء وتنسيق المواعيد'],
                [
                    'user_id' => $firstEmployee->user_id ?? $adminId,
                    'employee_id' => $firstEmployee->id,
                    'is_anonymous' => true, // سري
                    'type' => 'suggestion',
                    'priority' => 'low',
                    'content' => 'أقترح تقسيم فترات استراحة الغداء إلى فترتين بالتناوب لتفادي التزاحم في منطقة الاستراحة وضمان استمرارية الرد على العملاء دون تأخير.',
                    'against_manager' => false,
                    'status' => 'in_progress',
                    'hr_response' => 'فكرة ممتازة، جاري دراسة تطبيقها بالتنسيق مع المشرفين المباشرين.',
                    'assigned_to' => $adminId,
                ]
            );
        }
    }
}
