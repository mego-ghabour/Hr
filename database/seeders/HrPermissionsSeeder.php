<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class HrPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $hrPermissions = [
            // Employees
            'view_employee',
            'view_any_employee',
            'create_employee',
            'update_employee',
            'delete_employee',

            // Attendance
            'view_attendance',
            'view_any_attendance',
            'create_attendance',
            'update_attendance',
            'delete_attendance',

            // Leaves
            'view_leave',
            'view_any_leave',
            'create_leave',
            'update_leave',
            'delete_leave',

            // Leave Types
            'view_leave_type',
            'view_any_leave_type',
            'create_leave_type',
            'update_leave_type',
            'delete_leave_type',

            // Excuses
            'view_excuse',
            'view_any_excuse',
            'create_excuse',
            'update_excuse',
            'delete_excuse',

            // Job Postings
            'view_job_posting',
            'view_any_job_posting',
            'create_job_posting',
            'update_job_posting',
            'delete_job_posting',

            // Announcements
            'view_announcement',
            'view_any_announcement',
            'create_announcement',
            'update_announcement',
            'delete_announcement',

            // Payroll
            'view_payroll',
            'view_any_payroll',
            'create_payroll',
            'update_payroll',
            'delete_payroll',
        ];

        foreach ($hrPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Give all permissions to Super Admin and admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
        }

        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo(Permission::all());
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
