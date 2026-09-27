<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // مسح الكاش لتفادي أخطاء Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. إنشاء الصلاحيات (أمثلة)
        $permissions = [
            'view_talent', 'view_any_talent', 'create_talent', 'update_talent', 'delete_talent', 'restore_talent', 'force_delete_talent',
            'view_department', 'view_any_department', 'create_department', 'update_department', 'delete_department',
            'view_location', 'view_any_location', 'create_location', 'update_location', 'delete_location',
            'view_source', 'view_any_source', 'create_source', 'update_source', 'delete_source',
            'view_status', 'view_any_status', 'create_status', 'update_status', 'delete_status',
            'view_duplicate', 'view_any_duplicate', 'merge_duplicate', 'reject_duplicate',
            'manage_follow_ups', 'manage_reviews', 'manage_documents', 'manage_notes',
            'view_roles', 'manage_roles', 'view_users', 'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. إنشاء الأدوار وتخصيص الصلاحيات
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        // Super Admin gets all permissions dynamically via Gate::before in AuthServiceProvider (or we just assign all)
        $superAdmin->givePermissionTo(Permission::all());

        $hrRole = Role::firstOrCreate(['name' => 'HR Manager']);
        $hrRole->syncPermissions([
            'view_any_talent',
            'view_talent',
            'create_talent',
            'update_talent',
            'delete_talent',
        ]);

        $interviewerRole = Role::firstOrCreate(['name' => 'Interviewer']);
        $interviewerRole->syncPermissions([
            'view_any_talent',
            'view_talent',
        ]);

        // 3. تخصيص دور الـ Super Admin للمستخدم الأساسي
        $adminUser = User::where('email', 'admin@hr.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('Super Admin');
        }
    }
}
