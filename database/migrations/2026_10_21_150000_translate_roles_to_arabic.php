<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $translations = [
            'Super Admin' => 'المدير العام',
            'admin' => 'مسؤول النظام',
            'HR Manager' => 'مدير الموارد البشرية',
            'recruiter' => 'مسؤول التوظيف',
            'manager' => 'مدير قسم',
            'Interviewer' => 'مسؤول المقابلات',
            'Employee' => 'موظف',
        ];

        foreach ($translations as $en => $ar) {
            DB::table('roles')->where('name', $en)->update(['name' => $ar]);
        }
    }

    public function down(): void
    {
        $translations = [
            'المدير العام' => 'Super Admin',
            'مسؤول النظام' => 'admin',
            'مدير الموارد البشرية' => 'HR Manager',
            'مسؤول التوظيف' => 'recruiter',
            'مدير قسم' => 'manager',
            'مسؤول المقابلات' => 'Interviewer',
            'موظف' => 'Employee',
        ];

        foreach ($translations as $ar => $en) {
            DB::table('roles')->where('name', $ar)->update(['name' => $en]);
        }
    }
};
