<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DropTeacherIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Check if column exists
            $hasTeacherId = Schema::hasColumn('courses', 'teacher_id');
            
            if ($hasTeacherId) {
                // Drop foreign key constraint
                $this->command->info('Dropping foreign key constraint...');
                
                // Find the constraint name
                $query = "SELECT name FROM sys.foreign_keys WHERE parent_object_id = OBJECT_ID('courses') AND referenced_object_id = OBJECT_ID('users')";
                $constraints = DB::select($query);
                
                foreach ($constraints as $constraint) {
                    // Check if it's for teacher_id column
                    $columnQuery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME = '{$constraint->name}' AND COLUMN_NAME = 'teacher_id'";
                    $columns = DB::select($columnQuery);
                    
                    if (!empty($columns)) {
                        DB::statement("ALTER TABLE courses DROP CONSTRAINT {$constraint->name}");
                        $this->command->info("Dropped constraint: {$constraint->name}");
                    }
                }
                
                // Drop the column
                DB::statement('ALTER TABLE courses DROP COLUMN teacher_id');
                $this->command->info('Column teacher_id dropped successfully');
            } else {
                $this->command->info('Column teacher_id does not exist');
            }
        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
}
