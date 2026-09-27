<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // حذف الأقسام الحالية
        Department::query()->delete();

        $departments = [
            'Sales Specialist',
            'SEO Specialist',
            'Media Buyer',
            'Account Manager',
            'Data Entry',
            'Video Editor',
            'Graphic Designer',
            'UI/UX Designer',
            'Motion Graphic',
            'Social Media Specialist',
            'SEO Content Writer',
            'Off-Page SEO Specialist',
            'Wordpress Developer',
            'Salla/Zid Developer',
            'Influencers Specialist',
            'Store Manager',
            'Data Analysis',
            'Marketing Manager',
        ];

        foreach ($departments as $department) {
            Department::create(['name' => $department]);
        }
    }
}
