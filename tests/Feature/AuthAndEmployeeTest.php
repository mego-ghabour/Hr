<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Filament\Pages\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthAndEmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Department $department;
    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $this->adminUser = User::factory()->create([
            'email' => 'admin@system.com',
            'password' => bcrypt('password123'),
        ]);
        $this->adminUser->assignRole($role);

        $this->department = Department::create(['name' => 'الهندسة والتطوير']);

        $this->employee = Employee::create([
            'user_id' => $this->adminUser->id,
            'department_id' => $this->department->id,
            'first_name' => 'محمد',
            'last_name' => 'علي',
            'phone' => '01012345678',
            'job_title' => 'مدير تقني',
            'salary' => 15000,
            'hire_date' => '2026-01-01',
            'status' => 'active',
        ]);
    }

    public function test_can_authenticate_and_access_admin_panel(): void
    {
        // Unauthenticated access redirects to login
        $this->get('/admin')->assertRedirect('/admin/login');

        // Login page is accessible
        $this->get('/admin/login')->assertSuccessful();

        // Authenticated user can access panel
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertSuccessful();

        $this->assertAuthenticatedAs($this->adminUser);
    }

    public function test_can_view_employees_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/employees');
        $response->assertStatus(200);
        $response->assertSee('محمد');
        $response->assertSee('الهندسة والتطوير');
    }

    public function test_attendance_record_creation_and_calculation(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        $attendance = Attendance::create([
            'employee_id' => $this->employee->id,
            'date' => $today,
            'check_in_time' => '09:00:00',
            'check_out_time' => '17:00:00',
            'status' => 'present',
        ]);

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->employee->id,
            'date' => $today,
            'status' => 'present',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/attendances');
        $response->assertStatus(200);
        $response->assertSee('محمد علي');
    }

    public function test_leave_request_flow_and_type_relationship(): void
    {
        $leaveType = LeaveType::create([
            'name' => 'إجازة اعتيادية سنوية',
            'default_days_per_year' => 21,
            'requires_approval' => true,
        ]);

        $leave = Leave::create([
            'employee_id' => $this->employee->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => Carbon::tomorrow()->toDateString(),
            'end_date' => Carbon::tomorrow()->addDays(3)->toDateString(),
            'status' => 'pending',
            'reason' => 'ظرف شخصي',
        ]);

        $this->assertDatabaseHas('leaves', [
            'employee_id' => $this->employee->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/leaves');
        $response->assertStatus(200);
        $response->assertSee('محمد علي');
    }

    public function test_payroll_creation_and_net_salary_calculation(): void
    {
        $basic = 12000.00;
        $allowances = 2000.00;
        $deductions = 500.00;
        $net = $basic + $allowances - $deductions;

        $payroll = Payroll::create([
            'employee_id' => $this->employee->id,
            'month' => (int) date('m'),
            'year' => (int) date('Y'),
            'basic_salary' => $basic,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'net_salary' => $net,
            'status' => 'approved',
            'payment_method' => 'bank_transfer',
        ]);

        $this->assertEquals(13500.00, $payroll->net_salary);

        $response = $this->actingAs($this->adminUser)->get('/admin/payrolls');
        $response->assertStatus(200);
        $response->assertSee('محمد علي');
    }
}
