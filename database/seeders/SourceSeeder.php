<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Source;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        // حذف المصادر الحالية
        Source::query()->delete();

        $sources = [
            'لينكد إن',
            'ترشيح داخلي',
            'مواقع التوظيف',
            'موقع الشركة',
            'وكالة توظيف',
            'معرض توظيف',
            'السوشيال ميديا',
            'تقديم مباشر',
            'فيسبوك',
            'واتساب',
            'إعلان جريدة',
            'معارف شخصية',
        ];

        foreach ($sources as $source) {
            Source::create(['name' => $source]);
        }
    }
}
