<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Ensure all core employee-level permissions exist
        $employeePermissions = [
            'view_any_attendance',
            'view_attendance',
            'create_attendance',
            'view_any_leave',
            'view_leave',
            'create_leave',
            'view_leave_type',
            'view_any_excuse',
            'view_excuse',
            'create_excuse',
            'view_any_payroll',
            'view_payroll',
            'view_any_announcement',
            'view_announcement',
        ];

        foreach ($employeePermissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // 2. Assign these permissions to 'موظف' and 'Employee' roles
        $employeeRoles = Role::whereIn('name', ['موظف', 'Employee', 'employee'])->get();

        foreach ($employeeRoles as $role) {
            $role->syncPermissions($employeePermissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $employeeRoles = Role::whereIn('name', ['موظف', 'Employee', 'employee'])->get();
        foreach ($employeeRoles as $role) {
            $role->syncPermissions([]);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
