<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionModuleService
{
    /**
     * قائمة الصفحات والوحدات الرئيسية في النظام مع صلاحياتها المرتبطة
     * مطابقة للشاشات والتصميم الاحترافي الحديث
     */
    public static function getModules(): array
    {
        return [
            'quick_setup' => [
                'title' => 'إعداد سريع',
                'icon' => '⚡',
                'description' => 'المعالج السريع وإعدادات التهيئة الأولية',
                'permissions' => [
                    'view_roles',
                    'manage_roles',
                    'view_users',
                    'manage_users',
                ],
            ],
            'dashboard' => [
                'title' => 'لوحة التحكم',
                'icon' => '🏠',
                'description' => 'مؤشرات الأداء والإحصائيات العامة للمنشأة',
                'permissions' => [
                    'view_any_employee',
                    'view_any_attendance',
                    'view_any_leave',
                ],
            ],
            'employees' => [
                'title' => 'إدارة الموظفين',
                'icon' => '👥',
                'description' => 'ملفات الموظفين، العقود، والتعديل الإداري',
                'permissions' => [
                    'view_any_employee',
                    'view_employee',
                    'create_employee',
                    'update_employee',
                    'delete_employee',
                ],
            ],
            'org_structure' => [
                'title' => 'الهيكل الوظيفي',
                'icon' => '🪪',
                'description' => 'المخطط التنظيمي، الأقسام، والمواقع والفروع',
                'permissions' => [
                    'view_any_department',
                    'view_department',
                    'create_department',
                    'update_department',
                    'delete_department',
                    'view_any_location',
                    'view_location',
                    'create_location',
                    'update_location',
                ],
            ],
            'job_descriptions' => [
                'title' => 'الوصف الوظيفي',
                'icon' => '📄',
                'description' => 'الوظائف الشاغرة ومواصفات ومعايير المهام',
                'permissions' => [
                    'view_any_job_posting',
                    'view_job_posting',
                    'create_job_posting',
                    'update_job_posting',
                    'delete_job_posting',
                ],
            ],
            'recruitment_ats' => [
                'title' => 'نظام تتبع المتقدمين',
                'icon' => '🌪️',
                'description' => 'إدارة التوظيف، فحص السير الذاتية، ومراحل الترشيح',
                'permissions' => [
                    'view_any_talent',
                    'view_talent',
                    'create_talent',
                    'update_talent',
                    'delete_talent',
                    'restore_talent',
                    'force_delete_talent',
                    'manage_follow_ups',
                    'manage_reviews',
                    'manage_documents',
                    'manage_notes',
                    'view_any_duplicate',
                    'view_duplicate',
                    'merge_duplicate',
                    'reject_duplicate',
                    'view_any_source',
                    'view_any_status',
                ],
            ],
            'announcements' => [
                'title' => 'صانع الإعلانات',
                'icon' => '🖋️',
                'description' => 'نشر وتصميم التعاميم الإدارية والإعلانات الداخلية',
                'permissions' => [
                    'view_any_announcement',
                    'view_announcement',
                    'create_announcement',
                    'update_announcement',
                    'delete_announcement',
                ],
            ],
            'company_settings' => [
                'title' => 'إعدادات الشركة',
                'icon' => '⚙️',
                'description' => 'بيانات المنشأة، هوية النظام، والصلاحيات العامة',
                'permissions' => [
                    'view_users',
                    'manage_users',
                    'view_roles',
                    'manage_roles',
                    'view_any_location',
                    'create_location',
                    'update_location',
                    'delete_location',
                    'view_any_source',
                    'create_source',
                    'update_source',
                    'delete_source',
                    'view_any_status',
                    'create_status',
                    'update_status',
                    'delete_status',
                ],
            ],
            'attendance' => [
                'title' => 'إدارة الحضور',
                'icon' => '🕒',
                'description' => 'سجلات الدوام والبصمة وطلبات الاستئذان',
                'permissions' => [
                    'view_any_attendance',
                    'view_attendance',
                    'create_attendance',
                    'update_attendance',
                    'delete_attendance',
                    'view_any_excuse',
                    'view_excuse',
                    'create_excuse',
                    'update_excuse',
                    'delete_excuse',
                ],
            ],
            'leaves' => [
                'title' => 'الإجازات',
                'icon' => '📅',
                'description' => 'طلبات الإجازات، أرصدة الموظفين، وأنواع العطلات',
                'permissions' => [
                    'view_any_leave',
                    'view_leave',
                    'create_leave',
                    'update_leave',
                    'delete_leave',
                    'view_any_leave_type',
                    'view_leave_type',
                    'create_leave_type',
                    'update_leave_type',
                    'delete_leave_type',
                ],
            ],
            'payrolls' => [
                'title' => 'مسيرات الرواتب',
                'icon' => '💵',
                'description' => 'إعداد ومراجعة المسيرات الشهرية وقسائم الصرف',
                'permissions' => [
                    'view_any_payroll',
                    'view_payroll',
                    'create_payroll',
                    'update_payroll',
                    'delete_payroll',
                ],
            ],
            'grievances' => [
                'title' => 'صندوق الشكاوى والمقترحات',
                'icon' => '🛡️',
                'description' => 'فحص البلاغات، التحقيق المحايد، وحوكمة بيئة العمل',
                'permissions' => [
                    'view_any_grievance',
                    'manage_grievances',
                ],
            ],
            'evaluations' => [
                'title' => 'تقييم الأداء والمقابلات',
                'icon' => '⭐',
                'description' => 'نماذج التقييم الدوري ونتائج المقابلات الوظيفية',
                'permissions' => [
                    'manage_reviews',
                    'manage_notes',
                ],
            ],
            'activity_logs' => [
                'title' => 'سجلات التدقيق والنشاط',
                'icon' => '📋',
                'description' => 'مراقبة العمليات الحساسة وسجل أحداث النظام',
                'permissions' => [
                    'view_users',
                    'manage_users',
                ],
            ],
        ];
    }

    /**
     * استرجاع المفاتيح النشطة للسجل المحدد (دور أو مستخدم أو موظف)
     */
    public static function getActiveModulesForRecord($record): array
    {
        if (! $record) {
            return [];
        }

        $allModules = static::getModules();

        // المدير العام يمتلك افتراضياً كل الصلاحيات
        if (($record instanceof Role && in_array($record->name, ['Super Admin', 'المدير العام', 'admin'])) ||
            ($record instanceof User && $record->hasRole(['Super Admin', 'المدير العام', 'admin']))) {
            return array_keys($allModules);
        }

        $currentPerms = [];
        if ($record instanceof Role) {
            $currentPerms = $record->permissions->pluck('name')->toArray();
        } elseif ($record instanceof User) {
            $currentPerms = $record->getAllPermissions()->pluck('name')->toArray();
        } elseif ($record instanceof Employee && $record->user) {
            $currentPerms = $record->user->getAllPermissions()->pluck('name')->toArray();
        }

        $active = [];
        foreach ($allModules as $key => $module) {
            // يعتبر الموديول نشطاً إذا كان السجل يمتلك أي صلاحية تابعة له
            if (! empty(array_intersect($module['permissions'], $currentPerms))) {
                $active[] = $key;
            }
        }

        return $active;
    }

    /**
     * مزامنة الصلاحيات وفقاً للموديولات والصفحات المحددة
     */
    public static function syncModulesToRecord($record, array $selectedModuleKeys): void
    {
        if (! $record) {
            return;
        }

        $allModules = static::getModules();
        $permissionsToSync = [];

        foreach ($selectedModuleKeys as $key) {
            if (isset($allModules[$key])) {
                $permissionsToSync = array_merge($permissionsToSync, $allModules[$key]['permissions']);
            }
        }

        $permissionsToSync = array_values(array_unique($permissionsToSync));

        // التأكد من وجود الصلاحيات في قاعدة البيانات
        foreach ($permissionsToSync as $pName) {
            Permission::firstOrCreate(['name' => $pName, 'guard_name' => 'web']);
        }

        if ($record instanceof Role) {
            $record->syncPermissions($permissionsToSync);
        } elseif ($record instanceof User) {
            $record->syncPermissions($permissionsToSync);
        } elseif ($record instanceof Employee && $record->user) {
            $record->user->syncPermissions($permissionsToSync);
        }
    }
}
