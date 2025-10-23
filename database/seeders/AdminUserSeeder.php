<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@doraedu.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Teacher',
            'email' => 'teacher@doraedu.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Student',
            'email' => 'student@doraedu.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
    }
}
