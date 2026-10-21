<?php

namespace Tests\Feature;

use App\Filament\Admin\Pages\Settings;
use App\Models\Department;
use App\Models\LeaveType;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Source;
use App\Models\TalentStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SettingsPageTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $this->adminUser = User::factory()->create([
            'email' => 'superadmin@hr-test.com',
        ]);
        $this->adminUser->assignRole($role);
    }

    public function test_settings_hub_loads_successfully_with_all_grid_sections(): void
    {
        Department::create(['name' => 'إدارة التقنية والبرمجة']);
        Location::create(['name' => 'فرع الإسكندرية']);
        Source::create(['name' => 'معرض التوظيف']);
        TalentStatus::create(['name' => 'مقابلة أولى', 'color' => 'info', 'is_default' => false]);
        LeaveType::create(['name' => 'إجازة سنوية', 'default_days_per_year' => 21, 'requires_approval' => true]);

        $response = $this->actingAs($this->adminUser)->get(Settings::getUrl());
        $response->assertStatus(200);

        // Verify All 10 Grid Modules exist in flat content
        $content = $response->getContent();
        $this->assertStringContainsString('الأقسام', $content);
        $this->assertStringContainsString('المواقع', $content);
        $this->assertStringContainsString('إعدادات النظام', $content);
        $this->assertStringContainsString('مواعيد الدوام الرسمي', $content);
        $this->assertStringContainsString('مصادر الترشيح', $content);
        $this->assertStringContainsString('حالات المرشحين', $content);
        $this->assertStringContainsString('قوالب المراسلة', $content);
        $this->assertStringContainsString('خيارات الإتاحة', $content);
        $this->assertStringContainsString('أنواع وسياسات الإجازات', $content);
        $this->assertStringContainsString('تنبيهات العقود والإشعارات', $content);
    }

    public function test_can_navigate_directly_to_sections_and_back_to_grid(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->assertSet('activeTab', 'grid')
            ->assertSee('الإعدادات العامة والهيكل التنظيمي')
            ->assertSee('إعدادات النظام ومسار التوظيف')
            ->assertSee('سياسات الموارد البشرية والتنبيهات')
            ->call('openSection', 'shifts')
            ->assertSet('activeTab', 'shifts')
            ->assertSee('مواعيد وساعات الدوام الرسمي')
            ->call('backToGrid')
            ->assertSet('activeTab', 'grid')
            ->call('openSection', 'leaves')
            ->assertSet('activeTab', 'leaves')
            ->assertSee('أنواع وسياسات الإجازات')
            ->call('backToCurrentGrid')
            ->assertSet('activeTab', 'grid');
    }

    public function test_can_update_general_system_settings_currency_and_timezone(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('brand_name', 'شركة النخبة للتطوير')
            ->set('currency', 'SAR')
            ->assertSet('currency_symbol', 'ر.س')
            ->set('timezone', 'Asia/Riyadh')
            ->set('date_format', 'd/m/Y')
            ->set('brand_primary_color', '#0f766e')
            ->set('font_family', 'Tajawal')
            ->set('sidebar_theme', 'dark_navy')
            ->call('saveGeneralSettings')
            ->assertHasNoErrors();

        $this->assertEquals('شركة النخبة للتطوير', Setting::get('brand_name'));
        $this->assertEquals('SAR', Setting::currency());
        $this->assertEquals('ر.س', Setting::currencySymbol());
        $this->assertEquals('Asia/Riyadh', Setting::timezone());
        $this->assertEquals('Asia/Riyadh', config('app.timezone'));
        $this->assertEquals('d/m/Y', Setting::dateFormat());
        $this->assertEquals('Tajawal', Setting::get('font_family'));
        $this->assertEquals('dark_navy', Setting::get('sidebar_theme'));
        $this->assertEquals('#0f766e', Setting::get('brand_primary_color'));
    }

    public function test_currency_and_timezone_reflect_dynamically_in_employee_salaries_and_widgets(): void
    {
        Setting::set('currency', 'SAR');
        Setting::set('currency_symbol', 'ر.س');
        Setting::set('timezone', 'Asia/Riyadh');
        date_default_timezone_set('Asia/Riyadh');
        config(['app.timezone' => 'Asia/Riyadh']);

        $dept = Department::create(['name' => 'إدارة التقنية']);
        $employee = \App\Models\Employee::create([
            'department_id' => $dept->id,
            'first_name' => 'أحمد',
            'last_name' => 'الغامدي',
            'job_title' => 'مطور برمجيات',
            'salary' => 7500,
            'status' => 'active',
            'hire_date' => '2026-01-01',
        ]);

        // 1. Check EmployeeStats widget on dashboard
        Livewire::actingAs($this->adminUser)
            ->test(\App\Filament\Admin\Widgets\EmployeeStats::class)
            ->assertSee('7,500.00 ر.س');

        // 2. Check Employees Table
        Livewire::actingAs($this->adminUser)
            ->test(\App\Filament\Admin\Resources\Employees\Pages\ListEmployees::class)
            ->assertSee('7,500.00 ر.س');
    }

    public function test_can_save_shift_and_working_hours_settings(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('work_start_time', '08:30')
            ->set('work_end_time', '16:30')
            ->set('grace_period_minutes', 20)
            ->set('weekend_days', ['friday', 'saturday'])
            ->set('allow_remote_clock_in', false)
            ->call('saveShiftSettings')
            ->assertHasNoErrors();

        $this->assertEquals('08:30', Setting::get('work_start_time'));
        $this->assertEquals('16:30', Setting::get('work_end_time'));
        $this->assertEquals(20, Setting::gracePeriod());
    }

    public function test_can_manage_leave_types_in_single_page(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('activeTab', 'leaves')
            ->set('leave_type_name', 'إجازة رعاية خاصة')
            ->set('leave_type_days', 10)
            ->set('leave_type_requires_approval', true)
            ->call('createLeaveType')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('leave_types', [
            'name'                  => 'إجازة رعاية خاصة',
            'default_days_per_year' => 10,
        ]);
    }

    public function test_can_save_contract_alerts_and_notification_settings(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('alert_contract_expiry_days', 45)
            ->set('notify_new_application', true)
            ->set('hr_notification_email', 'hr-manager@company.com')
            ->call('saveAlertSettings')
            ->assertHasNoErrors();

        $this->assertEquals(45, (int) Setting::get('alert_contract_expiry_days'));
        $this->assertEquals('hr-manager@company.com', Setting::get('hr_notification_email'));
    }

    public function test_can_manage_departments_in_single_page(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('activeTab', 'departments')
            ->set('dept_name', 'قسم المبيعات والتسويق')
            ->call('createDepartment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('departments', ['name' => 'قسم المبيعات والتسويق']);
    }

    public function test_can_manage_locations_in_single_page(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('activeTab', 'locations')
            ->set('loc_name', 'المقر الرئيسي - الرياض')
            ->call('createLocation')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('locations', ['name' => 'المقر الرئيسي - الرياض']);
    }

    public function test_can_manage_statuses_in_single_page(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(Settings::class)
            ->set('activeTab', 'statuses')
            ->set('status_name', 'تم التعاقد المبدئي')
            ->set('status_color', 'success')
            ->call('createStatus')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('talent_statuses', ['name' => 'تم التعاقد المبدئي', 'color' => 'success']);
    }
}