<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TalentStatus;

class TalentStatusSeeder extends Seeder
{
    public function run(): void
    {
        // حذف الحالات الحالية
        TalentStatus::query()->delete();

        $statuses = [
            ['name' => 'جديد', 'color' => 'info', 'is_default' => true, 'order_column' => 1],
            ['name' => 'قيد المراجعة', 'color' => 'warning', 'is_default' => false, 'order_column' => 2],
            ['name' => 'في القائمة المختصرة', 'color' => 'success', 'is_default' => false, 'order_column' => 3],
            ['name' => 'مرحلة الإنترفيو', 'color' => 'purple', 'is_default' => false, 'order_column' => 4],
            ['name' => 'تم إرسال عرض', 'color' => 'orange', 'is_default' => false, 'order_column' => 5],
            ['name' => 'تم التعيين', 'color' => 'success', 'is_default' => false, 'order_column' => 6],
            ['name' => 'مرفوض', 'color' => 'danger', 'is_default' => false, 'order_column' => 7],
            ['name' => 'معلّق', 'color' => 'gray', 'is_default' => false, 'order_column' => 8],
            ['name' => 'أرشيف', 'color' => 'gray', 'is_default' => false, 'order_column' => 9],
        ];

        foreach ($statuses as $status) {
            TalentStatus::create($status);
        }
    }
}
