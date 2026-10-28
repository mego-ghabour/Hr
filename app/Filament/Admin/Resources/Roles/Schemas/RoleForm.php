<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function getPermissionGroups(): array
    {
        return [
            'employees' => [
                'title' => 'شؤون الموظفين',
                'icon' => 'heroicon-o-users',
                'field' => 'employee_permissions',
                'permissions' => [
                    'view_any_employee' => 'استعراض قائمة الموظفين',
                    'view_employee' => 'عرض الملف التفصيلي للموظف',
                    'create_employee' => 'إضافة موظف جديد',
                    'update_employee' => 'تعديل بيانات الموظف',
                    'delete_employee' => 'حذف موظف',
                ],
            ],
            'attendance' => [
                'title' => 'الحضور والاستئذانات',
                'icon' => 'heroicon-o-clock',
                'field' => 'attendance_permissions',
                'permissions' => [
                    'view_any_attendance' => 'استعراض الحضور والانصراف',
                    'view_attendance' => 'عرض تفاصيل بصمة الحضور',
                    'create_attendance' => 'تسجيل حضور يدوي',
                    'update_attendance' => 'تعديل سجل الحضور',
                    'delete_attendance' => 'حذف سجل الحضور',
                    'view_any_excuse' => 'استعراض طلبات الاستئذان',
                    'view_excuse' => 'عرض تفاصيل الاستئذان',
                    'create_excuse' => 'إضافة طلب استئذان',
                    'update_excuse' => 'الموافقة / تعديل الاستئذان',
                    'delete_excuse' => 'حذف طلب استئذان',
                ],
            ],
            'leaves' => [
                'title' => 'الإجازات والأرصدة',
                'icon' => 'heroicon-o-calendar-days',
                'field' => 'leave_permissions',
                'permissions' => [
                    'view_any_leave' => 'استعراض طلبات الإجازات',
                    'view_leave' => 'عرض تفاصيل طلب الإجازة',
                    'create_leave' => 'إضافة طلب إجازة',
                    'update_leave' => 'الموافقة / تعديل طلب الإجازة',
                    'delete_leave' => 'حذف طلب إجازة',
                    'view_any_leave_type' => 'استعراض أنواع الإجازات',
                    'view_leave_type' => 'عرض نوع إجازة',
                    'create_leave_type' => 'إضافة نوع إجازة جديد',
                    'update_leave_type' => 'تعديل نوع إجازة',
                    'delete_leave_type' => 'حذف نوع إجازة',
                ],
            ],
            'payrolls' => [
                'title' => 'مسيرات الرواتب',
                'icon' => 'heroicon-o-banknotes',
                'field' => 'payroll_permissions',
                'permissions' => [
                    'view_any_payroll' => 'استعراض مسيرات الرواتب',
                    'view_payroll' => 'عرض قسيمة ومفردات الراتب',
                    'create_payroll' => 'إنشاء مسير رواتب شهري',
                    'update_payroll' => 'تعديل واعتماد مسير الراتب',
                    'delete_payroll' => 'حذف مسير راتب',
                ],
            ],
            'recruitment' => [
                'title' => 'التوظيف واستقطاب المواهب',
                'icon' => 'heroicon-o-briefcase',
                'field' => 'recruitment_permissions',
                'permissions' => [
                    'view_any_talent' => 'استعراض المرشحين والمواهب',
                    'view_talent' => 'عرض الملف الشخصي للمرشح',
                    'create_talent' => 'إضافة مرشح جديد',
                    'update_talent' => 'تعديل بيانات المرشح',
                    'delete_talent' => 'حذف مرشح',
                    'restore_talent' => 'استعادة مرشح محذوف',
                    'force_delete_talent' => 'حذف مرشح نهائياً',
                    'manage_follow_ups' => 'إدارة المتابعات والتواصل',
                    'manage_reviews' => 'إدارة تقييمات المقابلات',
                    'manage_documents' => 'إدارة مستندات وسير المرشحين',
                    'manage_notes' => 'إدارة الملاحظات على المرشحين',
                    'view_any_duplicate' => 'استعراض المرشحين المكررين',
                    'view_duplicate' => 'عرض تفاصيل التكرار',
                    'merge_duplicate' => 'دمج المرشحين المكررين',
                    'reject_duplicate' => 'تجاهل / رفض التكرار',
                    'view_any_job_posting' => 'استعراض الوظائف الشاغرة',
                    'view_job_posting' => 'عرض إعلان وظيفة',
                    'create_job_posting' => 'نشر وظيفة شاغرة جديدة',
                    'update_job_posting' => 'تعديل إعلان وظيفة',
                    'delete_job_posting' => 'حذف إعلان وظيفة',
                ],
            ],
            'system' => [
                'title' => 'إدارة النظام والأدوار والإعدادات',
                'icon' => 'heroicon-o-cog-6-tooth',
                'field' => 'system_permissions',
                'permissions' => [
                    'view_users' => 'استعراض المستخدمين',
                    'manage_users' => 'إدارة المستخدمين بالكامل',
                    'view_roles' => 'استعراض الأدوار والصلاحيات',
                    'manage_roles' => 'إدارة الأدوار بالكامل',
                    'view_any_department' => 'استعراض الأقسام',
                    'view_department' => 'عرض قسم',
                    'create_department' => 'إضافة قسم',
                    'update_department' => 'تعديل قسم',
                    'delete_department' => 'حذف قسم',
                    'view_any_location' => 'استعراض المواقع والفروع',
                    'view_location' => 'عرض موقع',
                    'create_location' => 'إضافة موقع',
                    'update_location' => 'تعديل موقع',
                    'delete_location' => 'حذف موقع',
                    'view_any_source' => 'استعراض مصادر الترشيح',
                    'view_source' => 'عرض مصدر ترشيح',
                    'create_source' => 'إضافة مصدر',
                    'update_source' => 'تعديل مصدر',
                    'delete_source' => 'حذف مصدر',
                    'view_any_status' => 'استعراض حالات التوظيف',
                    'view_status' => 'عرض حالة توظيف',
                    'create_status' => 'إضافة حالة',
                    'update_status' => 'تعديل حالة',
                    'delete_status' => 'حذف حالة',
                    'view_any_announcement' => 'استعراض التعاميم الإدارية',
                    'view_announcement' => 'عرض تعميم إداري',
                    'create_announcement' => 'نشر تعميم إداري جديد',
                    'update_announcement' => 'تعديل تعميم إداري',
                    'delete_announcement' => 'حذف تعميم إداري',
                ],
            ],
        ];
    }

    public static function getRoleLabel(string $name): string
    {
        return match ($name) {
            'Super Admin', 'المدير العام' => 'المدير العام',
            'admin', 'مسؤول النظام' => 'مسؤول النظام',
            'HR Manager', 'مدير الموارد البشرية' => 'مدير الموارد البشرية',
            'recruiter', 'مسؤول التوظيف' => 'مسؤول التوظيف',
            'manager', 'مدير قسم' => 'مدير قسم',
            'Interviewer', 'مسؤول المقابلات' => 'مسؤول المقابلات',
            'Employee', 'employee', 'موظف' => 'موظف',
            default => $name,
        };
    }

    public static function getPermissionLabel(string $permissionName): string
    {
        foreach (static::getPermissionGroups() as $group) {
            if (isset($group['permissions'][$permissionName])) {
                return $group['permissions'][$permissionName];
            }
        }

        return $permissionName;
    }

    public static function configure(Schema $schema): Schema
    {
        $tabs = [];

        foreach (static::getPermissionGroups() as $key => $group) {
            $tabs[] = Tabs\Tab::make($group['title'])
                ->icon($group['icon'])
                ->schema([
                    CheckboxList::make($group['field'])
                        ->label("صلاحيات {$group['title']}")
                        ->options($group['permissions'])
                        ->bulkToggleable()
                        ->columns(2)
                        ->gridDirection('row'),
                ]);
        }

        return $schema
            ->components([
                Section::make('معلومات الدور الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم الدور (Role Name)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('مثال: HR Specialist أو مدير قسم'),
                    ]),

                Section::make('إدارة صلاحيات الوصول للصفحات والوحدات')
                    ->description('حدد الصفحات والوحدات التي يمكن لهذا الدور الوصول إليها عبر تفعيل المفاتيح أدناه.')
                    ->schema([
                        \Filament\Forms\Components\ViewField::make('active_modules')
                            ->view('filament.admin.components.permission-matrix-modal')
                            ->columnSpanFull(),
                    ]),

                Section::make('الصلاحيات التفصيلية الدقيقة (متقدم)')
                    ->description('يمكنك تخصيص الأذونات الفردية بدقة لكل قسم إذا لزم الأمر.')
                    ->collapsed()
                    ->schema([
                        Tabs::make('RolePermissionsTabs')
                            ->tabs($tabs)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
