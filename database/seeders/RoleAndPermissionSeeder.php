<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view_talent', 'view_any_talent', 'create_talent', 'update_talent', 'delete_talent', 'restore_talent', 'force_delete_talent',
            'view_department', 'view_any_department', 'create_department', 'update_department', 'delete_department',
            'view_location', 'view_any_location', 'create_location', 'update_location', 'delete_location',
            'view_source', 'view_any_source', 'create_source', 'update_source', 'delete_source',
            'view_status', 'view_any_status', 'create_status', 'update_status', 'delete_status',
            'view_duplicate', 'view_any_duplicate', 'merge_duplicate', 'reject_duplicate',
            'manage_follow_ups', 'manage_reviews', 'manage_documents', 'manage_notes',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());
        
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo([
            'view_talent', 'view_any_talent', 'create_talent', 'update_talent', 'delete_talent',
            'view_department', 'view_any_department', 'view_location', 'view_any_location',
            'view_source', 'view_any_source', 'view_status', 'view_any_status',
            'view_duplicate', 'view_any_duplicate', 'merge_duplicate', 'reject_duplicate',
            'manage_follow_ups', 'manage_reviews', 'manage_documents', 'manage_notes',
        ]);
        
        $recruiter = Role::firstOrCreate(['name' => 'recruiter']);
        $recruiter->givePermissionTo([
            'view_talent', 'view_any_talent', 'create_talent', 'update_talent',
            'view_department', 'view_any_department', 'view_location', 'view_any_location',
            'view_source', 'view_any_source', 'view_status', 'view_any_status',
            'manage_follow_ups', 'manage_reviews', 'manage_notes',
        ]);
    }
}
