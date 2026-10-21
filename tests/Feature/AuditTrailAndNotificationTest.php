<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\User;
use App\Notifications\LeaveRequestedNotification;
use App\Notifications\LeaveStatusUpdatedNotification;
use App\Notifications\PayrollIssuedNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuditTrailAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $employeeUser;
    protected Employee $employee;
    protected Department $department;
    protected LeaveType $leaveType;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'المدير العام', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'موظف', 'guard_name' => 'web']);

        $this->adminUser = User::factory()->create([
            'email' => 'admin@company.com',
        ]);
        $this->adminUser->assignRole($adminRole);

        $this->department = Department::create(['name' => 'إدارة الهندسة']);

        $this->employeeUser = User::factory()->create([
            'email' => 'staff@company.com',
        ]);
        $this->employeeUser->assignRole($employeeRole);

        $this->employee = Employee::create([
            'user_id' => $this->employeeUser->id,
            'department_id' => $this->department->id,
            'first_name' => 'جمال',
            'last_name' => 'سالم',
            'phone' => '0552222222',
            'job_title' => 'مطور برمجيات',
            'salary' => 14000,
            'hire_date' => '2025-02-01',
            'status' => 'active',
        ]);

        $this->leaveType = LeaveType::firstOrCreate(
            ['name' => 'إجازة سنوية'],
            ['default_days_per_year' => 21, 'requires_approval' => true]
        );
    }

    public function test_admin_can_access_activity_logs_audit_trail_screen(): void
    {
        // Trigger an activity log
        activity()
            ->causedBy($this->adminUser)
            ->performedOn($this->employee)
            ->log('تم تعديل بيانات الموظف بنجاح');

        $response = $this->actingAs($this->adminUser)->get('/admin/activity-logs');
        $response->assertStatus(200);
        $response->assertSee('سجلات تدقيق ومراقبة العمليات');

        Livewire::actingAs($this->adminUser)
            ->test(ListActivityLogs::class)
            ->assertSee('تم تعديل بيانات الموظف بنجاح');
    }

    public function test_submitting_leave_sends_notification_to_admins(): void
    {
        Notification::fake();

        $leave = Leave::create([
            'employee_id' => $this->employee->id,
            'leave_type_id' => $this->leaveType->id,
            'start_date' => Carbon::today()->addDays(2)->format('Y-m-d'),
            'end_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
            'reason' => 'إجازة للاستجمام السنوي',
            'status' => 'pending',
        ]);

        Notification::assertSentTo(
            [$this->adminUser],
            LeaveRequestedNotification::class
        );
    }

    public function test_approving_leave_sends_notification_to_employee(): void
    {
        Notification::fake();

        $leave = Leave::create([
            'employee_id' => $this->employee->id,
            'leave_type_id' => $this->leaveType->id,
            'start_date' => Carbon::today()->addDays(3)->format('Y-m-d'),
            'end_date' => Carbon::today()->addDays(6)->format('Y-m-d'),
            'reason' => 'إجازة معتادة',
            'status' => 'pending',
        ]);

        // Admin approves the leave
        $leave->update([
            'status' => 'approved',
            'admin_notes' => 'تمت الموافقة مع تمنياتنا بإجازة سعيدة',
        ]);

        Notification::assertSentTo(
            [$this->employeeUser],
            LeaveStatusUpdatedNotification::class
        );
    }

    public function test_issuing_payroll_sends_notification_to_employee(): void
    {
        Notification::fake();

        $payroll = Payroll::create([
            'employee_id' => $this->employee->id,
            'month' => 10,
            'year' => 2026,
            'basic_salary' => 14000,
            'allowances' => 1000,
            'deductions' => 0,
            'net_salary' => 15000,
            'status' => 'draft',
        ]);

        // Status changed to paid
        $payroll->update([
            'status' => 'paid',
            'payment_date' => '2026-10-25',
        ]);

        Notification::assertSentTo(
            [$this->employeeUser],
            PayrollIssuedNotification::class
        );
    }

    public function test_changes_to_employee_and_payroll_are_recorded_in_activity_log(): void
    {
        $this->employee->update([
            'salary' => 16000,
            'job_title' => 'قائد فريق برمجي',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Employee::class,
            'subject_id' => $this->employee->id,
            'event' => 'updated',
        ]);

        $payroll = Payroll::create([
            'employee_id' => $this->employee->id,
            'month' => 11,
            'year' => 2026,
            'basic_salary' => 16000,
            'allowances' => 0,
            'deductions' => 0,
            'net_salary' => 16000,
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Payroll::class,
            'subject_id' => $payroll->id,
            'event' => 'created',
        ]);
    }
}
