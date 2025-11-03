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
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Teacher',
            'email' => 'teacher@doraedu.com',
            'password' => bcrypt('12345678'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Student',
            'email' => 'student@doraedu.com',
            'password' => bcrypt('12345678'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
    }
}
