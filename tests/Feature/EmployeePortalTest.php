<?php

namespace Tests\Feature;

use App\Livewire\Portal\EmployeePortal;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Excuse;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmployeePortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Employee $employee;
    protected Department $department;
    protected LeaveType $leaveType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->department = Department::create([
            'name' => 'إدارة التقنية',
        ]);

        $this->user = User::factory()->create([
            'email' => 'employee@company.com',
            'password' => bcrypt('password123'),
        ]);

        $this->employee = Employee::create([
            'user_id' => $this->user->id,
            'department_id' => $this->department->id,
            'first_name' => 'أحمد',
            'last_name' => 'محمود',
            'phone' => '0501234567',
            'job_title' => 'مهندس برمجيات',
            'salary' => 12000,
            'hire_date' => '2025-01-01',
            'status' => 'active',
            'bank_name' => 'مصرف الراجحي',
            'iban' => 'SA0380000000608010167519',
        ]);

        $this->leaveType = LeaveType::firstOrCreate(
            ['name' => 'إجازة سنوية'],
            ['default_days_per_year' => 21, 'requires_approval' => true]
        );
    }

    public function test_guest_is_redirected_to_portal_login(): void
    {
        $response = $this->get('/portal');
        $response->assertRedirect('/portal/login');
    }

    public function test_portal_login_page_renders_successfully(): void
    {
        $response = $this->get('/portal/login');
        $response->assertStatus(200);
        $response->assertSee('بوابة الخدمة الذاتية للموظفين');
        $response->assertSee('تسجيل الدخول إلى البوابة');
    }

    public function test_employee_can_login_via_portal(): void
    {
        $response = $this->post('/portal/login', [
            'email' => 'employee@company.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_employee_portal_dashboard_renders_for_authenticated_employee(): void
    {
        $response = $this->actingAs($this->user)->get('/portal');
        $response->assertStatus(200);
        $response->assertSee('أحمد');
        $response->assertSee('مهندس برمجيات');
        $response->assertSee('إدارة التقنية');
    }

    public function test_employee_can_clock_in_and_clock_out_via_livewire(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->call('clockIn')
            ->assertSet('flashType', 'success');

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->employee->id,
            'date' => $today,
            'status' => 'present',
        ]);

        // Clock out
        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->call('clockOut')
            ->assertSet('flashType', 'success');

        $attendance = Attendance::where('employee_id', $this->employee->id)->where('date', $today)->first();
        $this->assertNotNull($attendance->check_out_time);
    }

    public function test_employee_cannot_clock_in_twice(): void
    {
        $today = Carbon::today()->format('Y-m-d');
        Attendance::create([
            'employee_id' => $this->employee->id,
            'date' => $today,
            'check_in_time' => '08:30:00',
            'status' => 'present',
        ]);

        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->call('clockIn')
            ->assertSet('flashType', 'warning');

        $this->assertEquals(1, Attendance::where('employee_id', $this->employee->id)->where('date', $today)->count());
    }

    public function test_employee_can_view_leave_balances_and_submit_leave_request(): void
    {
        $startDate = Carbon::today()->addDays(5)->format('Y-m-d');
        $endDate = Carbon::today()->addDays(8)->format('Y-m-d');

        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->set('leave_type_id', $this->leaveType->id)
            ->set('start_date', $startDate)
            ->set('end_date', $endDate)
            ->set('leave_reason', 'إجازة عائلية سنوية معتادة')
            ->call('submitLeaveRequest')
            ->assertSet('flashType', 'success');

        $this->assertDatabaseHas('leaves', [
            'employee_id' => $this->employee->id,
            'leave_type_id' => $this->leaveType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => 'إجازة عائلية سنوية معتادة',
            'status' => 'pending',
        ]);
    }

    public function test_leave_request_validation_fails_on_invalid_dates(): void
    {
        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->set('leave_type_id', $this->leaveType->id)
            ->set('start_date', Carbon::today()->addDays(10)->format('Y-m-d'))
            ->set('end_date', Carbon::today()->addDays(2)->format('Y-m-d')) // end before start!
            ->set('leave_reason', 'اختبار')
            ->call('submitLeaveRequest')
            ->assertHasErrors(['end_date']);
    }

    public function test_employee_can_submit_excuse_request(): void
    {
        $date = Carbon::today()->format('Y-m-d');

        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->set('excuse_date', $date)
            ->set('excuse_start_time', '10:00')
            ->set('excuse_end_time', '12:00')
            ->set('excuse_reason', 'مراجعة مستشفى وإجراء فحص طبي')
            ->call('submitExcuseRequest')
            ->assertSet('flashType', 'success');

        $this->assertDatabaseHas('excuses', [
            'employee_id' => $this->employee->id,
            'date' => $date,
            'duration_minutes' => 120,
            'status' => 'pending',
        ]);
    }

    public function test_employee_can_view_monthly_payrolls_and_open_payslip(): void
    {
        $payroll = Payroll::create([
            'employee_id' => $this->employee->id,
            'month' => 10,
            'year' => 2026,
            'basic_salary' => 12000,
            'allowances' => 1500,
            'deductions' => 500,
            'net_salary' => 13000,
            'status' => 'paid',
            'payment_date' => '2026-10-25',
        ]);

        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->call('openPayslip', $payroll->id)
            ->assertSet('showPayslipModal', true)
            ->assertSet('selectedPayrollId', $payroll->id)
            ->assertSee('13,000.00')
            ->assertSee('مصرف الراجحي')
            ->call('closePayslipModal')
            ->assertSet('showPayslipModal', false);
    }

    public function test_employee_can_switch_tabs(): void
    {
        Livewire::actingAs($this->user)
            ->test(EmployeePortal::class)
            ->call('setTab', 'attendance')
            ->assertSet('activeTab', 'attendance')
            ->call('setTab', 'leaves')
            ->assertSet('activeTab', 'leaves')
            ->call('setTab', 'excuses')
            ->assertSet('activeTab', 'excuses')
            ->call('setTab', 'payrolls')
            ->assertSet('activeTab', 'payrolls')
            ->call('setTab', 'profile')
            ->assertSet('activeTab', 'profile');
    }

    public function test_employee_can_logout(): void
    {
        $response = $this->actingAs($this->user)->post('/portal/logout');
        $response->assertRedirect('/portal/login');
        $this->assertGuest();
    }
}
