<?php

namespace Tests\Feature;

use App\Filament\Admin\Pages\Settings;
use App\Livewire\Portal\EmployeePortal;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GpsAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $employeeUser;
    protected Employee $employee;
    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin User
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $this->adminUser = User::factory()->create(['email' => 'admin@company.com']);
        $this->adminUser->assignRole($role);

        // Employee User
        $this->department = Department::create(['name' => 'إدارة التقنية']);
        $this->employeeUser = User::factory()->create(['email' => 'ahmed@company.com']);
        $this->employee = Employee::create([
            'user_id' => $this->employeeUser->id,
            'department_id' => $this->department->id,
            'first_name' => 'أحمد',
            'last_name' => 'محمود',
            'job_title' => 'مطور برمجيات',
            'status' => 'active',
            'is_remote' => false,
        ]);
    }

    public function test_can_save_gps_geofence_settings_in_settings_hub(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('enable_gps_clockin', true)
            ->set('company_latitude', '24.713600')
            ->set('company_longitude', '46.675300')
            ->set('company_geofence_radius', 350)
            ->call('saveShiftSettings')
            ->assertHasNoErrors();

        $this->assertTrue((bool) Setting::get('enable_gps_clockin'));
        $this->assertEquals('24.713600', Setting::get('company_latitude'));
        $this->assertEquals('46.675300', Setting::get('company_longitude'));
        $this->assertEquals(350, (int) Setting::get('company_geofence_radius'));
    }

    public function test_clock_in_succeeds_when_employee_is_within_geofence_radius(): void
    {
        // Configure Office GPS
        Setting::set('enable_gps_clockin', true);
        Setting::set('company_latitude', '24.713600');
        Setting::set('company_longitude', '46.675300');
        Setting::set('company_geofence_radius', 200);

        // Employee clocks in ~10 meters away
        $empLat = 24.713650;
        $empLng = 46.675350;

        Livewire::actingAs($this->employeeUser)
            ->test(EmployeePortal::class)
            ->call('clockIn', $empLat, $empLng)
            ->assertSet('flashType', 'success');

        $today = Carbon::today()->format('Y-m-d');
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->employee->id,
            'date' => $today,
            'status' => 'present',
            'is_remote' => false,
        ]);

        $att = Attendance::where('employee_id', $this->employee->id)->where('date', $today)->first();
        $this->assertNotNull($att);
        $this->assertEquals($empLat, (float) $att->check_in_lat);
        $this->assertEquals($empLng, (float) $att->check_in_lng);
        $this->assertLessThanOrEqual(200, $att->distance_meters);
    }

    public function test_clock_in_is_rejected_when_employee_is_outside_geofence_radius(): void
    {
        // Configure Office GPS in Riyadh
        Setting::set('enable_gps_clockin', true);
        Setting::set('company_latitude', '24.713600');
        Setting::set('company_longitude', '46.675300');
        Setting::set('company_geofence_radius', 200);

        // Employee tries to clock in from Cairo (approx 1600 km away)
        $cairoLat = 30.044400;
        $cairoLng = 31.235700;

        Livewire::actingAs($this->employeeUser)
            ->test(EmployeePortal::class)
            ->call('clockIn', $cairoLat, $cairoLng)
            ->assertSet('flashType', 'warning');

        $today = Carbon::today()->format('Y-m-d');
        $this->assertDatabaseMissing('attendances', [
            'employee_id' => $this->employee->id,
            'date' => $today,
        ]);
    }

    public function test_clock_in_is_rejected_when_gps_is_required_and_coordinates_are_missing(): void
    {
        Setting::set('enable_gps_clockin', true);
        Setting::set('company_latitude', '24.713600');
        Setting::set('company_longitude', '46.675300');

        Livewire::actingAs($this->employeeUser)
            ->test(EmployeePortal::class)
            ->call('clockIn', null, null)
            ->assertSet('flashType', 'error');

        $today = Carbon::today()->format('Y-m-d');
        $this->assertDatabaseMissing('attendances', [
            'employee_id' => $this->employee->id,
            'date' => $today,
        ]);
    }

    public function test_remote_employee_can_clock_in_from_anywhere_even_when_gps_is_active(): void
    {
        // Configure strict office GPS
        Setting::set('enable_gps_clockin', true);
        Setting::set('company_latitude', '24.713600');
        Setting::set('company_longitude', '46.675300');
        Setting::set('company_geofence_radius', 100);

        // Mark employee as remote
        $this->employee->update(['is_remote' => true]);

        // Remote employee clocks in from another city/country
        $remoteLat = 30.044400;
        $remoteLng = 31.235700;

        Livewire::actingAs($this->employeeUser)
            ->test(EmployeePortal::class)
            ->call('clockIn', $remoteLat, $remoteLng)
            ->assertSet('flashType', 'success');

        $today = Carbon::today()->format('Y-m-d');
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->employee->id,
            'date' => $today,
            'status' => 'present',
            'is_remote' => true,
        ]);

        $att = Attendance::where('employee_id', $this->employee->id)->where('date', $today)->first();
        $this->assertNotNull($att);
        $this->assertEquals($remoteLat, (float) $att->check_in_lat);
        $this->assertTrue((bool) $att->is_remote);
    }

    public function test_clock_in_succeeds_from_anywhere_when_gps_verification_is_disabled(): void
    {
        // GPS verification explicitly disabled
        Setting::set('enable_gps_clockin', false);

        Livewire::actingAs($this->employeeUser)
            ->test(EmployeePortal::class)
            ->call('clockIn', null, null)
            ->assertSet('flashType', 'success');

        $today = Carbon::today()->format('Y-m-d');
        $this->assertDatabaseHas('attendances', [
            'employee_id' => $this->employee->id,
            'date' => $today,
            'status' => 'present',
        ]);
    }

    public function test_employee_can_clock_out_with_gps_coordinates(): void
    {
        $today = Carbon::today()->format('Y-m-d');
        $att = Attendance::create([
            'employee_id' => $this->employee->id,
            'date' => $today,
            'check_in_time' => '09:00:00',
            'status' => 'present',
        ]);

        Livewire::actingAs($this->employeeUser)
            ->test(EmployeePortal::class)
            ->call('clockOut', 24.713600, 46.675300)
            ->assertSet('flashType', 'success');

        $att->refresh();
        $this->assertNotNull($att->check_out_time);
        $this->assertEquals(24.713600, (float) $att->check_out_lat);
        $this->assertEquals(46.675300, (float) $att->check_out_lng);
    }
}
