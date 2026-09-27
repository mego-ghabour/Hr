<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@hr.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('admin');


        

    }
}

