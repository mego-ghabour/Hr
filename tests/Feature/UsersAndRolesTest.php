<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UsersAndRolesTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $employeeUser;
    protected Employee $employee;
    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'view_any_employee', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create_employee', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view_any_attendance', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view_users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage_users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view_roles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage_roles', 'guard_name' => 'web']);

        $this->adminUser = User::factory()->create([
            'email' => 'admin@system.com',
        ]);
        $this->adminUser->assignRole($superAdminRole);

        $this->department = Department::create(['name' => 'قسم التقنية']);

        $this->employeeUser = User::factory()->create([
            'email' => 'staff@system.com',
        ]);
        $this->employeeUser->assignRole($employeeRole);

        $this->employee = Employee::create([
            'user_id' => $this->employeeUser->id,
            'department_id' => $this->department->id,
            'first_name' => 'سامي',
            'last_name' => 'يوسف',
            'phone' => '0509999999',
            'job_title' => 'مطور واجهات',
            'salary' => 9000,
            'hire_date' => '2025-05-01',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_view_users_list_with_linked_employee_information(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('admin@system.com');
        $response->assertSee('staff@system.com');
        $response->assertSee('سامي يوسف');
        $response->assertSee('قسم التقنية');
    }

    public function test_admin_can_view_roles_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/roles');
        $response->assertStatus(200);
        $response->assertSee('Super Admin');
        $response->assertSee('Employee');
    }

    public function test_employee_accessing_admin_panel_is_redirected_to_portal(): void
    {
        $response = $this->actingAs($this->employeeUser)->get('/admin');
        $response->assertRedirect(route('portal.dashboard'));
    }

    public function test_filament_login_response_redirects_employee_to_portal(): void
    {
        $this->actingAs($this->employeeUser);
        $response = app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class)->toResponse(request());
        $this->assertEquals(route('portal.dashboard'), $response->getTargetUrl());
    }

    public function test_filament_login_response_redirects_admin_to_admin_panel(): void
    {
        $this->actingAs($this->adminUser);
        $response = app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class)->toResponse(request());
        $this->assertEquals(url('/admin'), $response->getTargetUrl());
    }

    public function test_super_admin_bypasses_all_gates_and_can_access_admin_panel(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertSuccessful();
    }

    public function test_can_link_employee_when_editing_user(): void
    {
        $newUser = User::factory()->create([
            'email' => 'unlinked@system.com',
        ]);

        $newEmployee = Employee::create([
            'department_id' => $this->department->id,
            'first_name' => 'خالد',
            'last_name' => 'عمر',
            'phone' => '0551111111',
            'job_title' => 'محاسب',
            'salary' => 8000,
            'hire_date' => '2025-06-01',
            'status' => 'active',
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(EditUser::class, ['record' => $newUser->id])
            ->fillForm([
                'employee_id' => $newEmployee->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertEquals($newUser->id, $newEmployee->fresh()->user_id);
    }

    public function test_can_create_role_with_grouped_permissions(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(CreateRole::class)
            ->fillForm([
                'name' => 'مسؤول الموظفين التجريبي',
                'employee_permissions' => ['view_any_employee', 'create_employee'],
                'attendance_permissions' => ['view_any_attendance'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $role = Role::where('name', 'مسؤول الموظفين التجريبي')->first();
        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermissionTo('view_any_employee'));
        $this->assertTrue($role->hasPermissionTo('create_employee'));
        $this->assertTrue($role->hasPermissionTo('view_any_attendance'));
    }
}
