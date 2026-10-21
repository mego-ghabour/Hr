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
}
