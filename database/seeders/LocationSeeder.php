<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // حذف المواقع الحالية
        Location::query()->delete();

        // مراكز ومدن محافظة الدقهلية
        $locations = [
            'المنصورة',
            'طلخا',
            'ميت غمر',
            'دكرنس',
            'أجا',
            'السنبلاوين',
            'منية النصر',
            'شربين',
            'بلقاس',
            'المنزلة',
            'تمي الأمديد',
            'الجمالية',
            'نبروه',
            'بني عبيد',
            'المطرية',
            'الكردي',
            'محلة دمنة',
            'جمصة',
            'الدقهلية',
        ];

        foreach ($locations as $location) {
            Location::create(['name' => $location]);
        }
    }
}
