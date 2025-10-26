<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCourseUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Check if table already exists
            if (Schema::hasTable('course_user')) {
                $this->command->info('Table course_user already exists');
                return;
            }

            // Create table
            DB::statement('CREATE TABLE course_user (
                id BIGINT IDENTITY(1,1) PRIMARY KEY,
                course_id BIGINT NOT NULL,
                user_id BIGINT NOT NULL,
                role NVARCHAR(255) NOT NULL DEFAULT \'student\',
                enrolled_at DATETIME NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )');

            // Create composite unique constraint
            DB::statement('CREATE UNIQUE INDEX course_user_role_unique ON course_user (course_id, user_id, role)');

            $this->command->info('Table course_user created successfully');

        } catch (\Exception $e) {
            $this->command->error('Error creating table: ' . $e->getMessage());
        }
    }
}
