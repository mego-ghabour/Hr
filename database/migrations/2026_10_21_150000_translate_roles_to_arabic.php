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
            try {
                $existingAr = DB::table('roles')->where('name', $ar)->first();
                $existingEn = DB::table('roles')->where('name', $en)->first();

                if ($existingEn && $existingAr) {
                    // Both exist: re-link model_has_roles and role_has_permissions to Arabic role, then delete En
                    DB::table('model_has_roles')->where('role_id', $existingEn->id)->update(['role_id' => $existingAr->id]);
                    DB::table('role_has_permissions')->where('role_id', $existingEn->id)->update(['role_id' => $existingAr->id]);
                    DB::table('roles')->where('id', $existingEn->id)->delete();
                } elseif ($existingEn && !$existingAr) {
                    DB::table('roles')->where('id', $existingEn->id)->update(['name' => $ar]);
                }
            } catch (\Throwable) {}
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
            try {
                DB::table('roles')->where('name', $ar)->update(['name' => $en]);
            } catch (\Throwable) {}
        }
    }
};
