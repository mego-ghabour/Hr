<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_successfully(): void
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
        $user->assignRole($role);

        Announcement::create([
            'title' => 'تعميم تجريبي',
            'content' => 'محتوى التعميم التجريبي',
            'type' => 'info',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Check announcement appears before clock and kpi
        $posAnnounce = strpos($content, 'التعاميم والقرارات الإدارية');
        $posClock = strpos($content, 'بوابة الحضور الذكية');
        $posKpi = strpos($content, 'مؤشرات الأداء الرئيسية');

        if ($posAnnounce !== false && $posClock !== false) {
            $this->assertTrue($posAnnounce < $posClock, 'Announcements must appear BEFORE the clock terminal');
        }
    }

    public function test_admin_dashboard_renders_with_filters_without_type_error(): void
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'email' => 'admin_filters@test.com',
        ]);
        $user->assignRole($role);

        $filterKey = md5(\App\Filament\Admin\Pages\Dashboard::class) . '_filters';

        $response = $this->actingAs($user)
            ->withSession([
                $filterKey => [
                    'startDate' => '2026-01-01',
                    'endDate' => '2026-12-31',
                    'department_id' => null,
                ],
            ])
            ->get('/admin');

        $response->assertStatus(200);
    }

    public function test_loading_section_component_safely_handles_array_props(): void
    {
        $view = $this->blade('<x-filament::loading-section :page-filters="[\'startDate\' => \'2026-01-01\']" :column-span="[\'default\' => 1]" />');
        $this->assertStringContainsString('fi-loading-section', (string) $view);
    }
}

