<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin users
        User::factory()
            ->admin()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@doraedu.com',
            ]);

        // Create additional admin users
        User::factory()->admin()->count(2)->create();

        // Create teacher users
        User::factory()
            ->teacher()
            ->create([
                'name' => 'Teacher',
                'email' => 'teacher@doraedu.com',
            ]);

        // Create additional teacher users
        User::factory()->teacher()->count(5)->create();

        // Create student users
        User::factory()
            ->student()
            ->create([
                'name' => 'Student',
                'email' => 'student@doraedu.com',
            ]);

        // Create additional student users
        User::factory()->student()->count(20)->create();

        $this->command->info('Users seeded successfully!');
        $this->command->info('- 3 Admin users (including admin@doraedu.com)');
        $this->command->info('- 6 Teacher users (including teacher@doraedu.com)');
        $this->command->info('- 21 Student users (including student@doraedu.com)');
    }
}

