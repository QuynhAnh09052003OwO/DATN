<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeDurationToDecimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Check column type
            $query = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'courses' AND COLUMN_NAME = 'duration'";
            $result = DB::select($query);
            
            if (!empty($result) && $result[0]->DATA_TYPE === 'int') {
                $this->command->info('Changing duration column from INT to DECIMAL...');
                
                // Convert existing data from minutes to hours
                // First get current values
                $courses = DB::select("SELECT id, duration FROM courses WHERE duration IS NOT NULL");
                
                // Create temporary column with DECIMAL (check if exists first)
                $tempExists = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'courses' AND COLUMN_NAME = 'duration_temp'");
                
                if (empty($tempExists)) {
                    DB::statement('ALTER TABLE courses ADD duration_temp DECIMAL(8,2)');
                } else {
                    $this->command->info('Column duration_temp already exists, skipping...');
                }
                
                // Convert and populate temporary column
                foreach ($courses as $course) {
                    // Convert minutes to hours
                    $hours = $course->duration / 60.0;
                    DB::statement("UPDATE courses SET duration_temp = ? WHERE id = ?", [$hours, $course->id]);
                }
                
                // Drop constraint first
                $constraintQuery = "SELECT name FROM sys.default_constraints WHERE parent_object_id = OBJECT_ID('courses') AND parent_column_id = COLUMNPROPERTY(OBJECT_ID('courses'), 'duration', 'ColumnId')";
                $constraints = DB::select($constraintQuery);
                
                foreach ($constraints as $constraint) {
                    DB::statement("ALTER TABLE courses DROP CONSTRAINT {$constraint->name}");
                    $this->command->info("Dropped constraint: {$constraint->name}");
                }
                
                // Drop old column
                DB::statement('ALTER TABLE courses DROP COLUMN duration');
                
                // Rename temporary column
                DB::statement('EXEC sp_rename \'courses.duration_temp\', \'duration\', \'COLUMN\'');
                
                $this->command->info('Column duration changed to DECIMAL(8,2) successfully');
            } elseif (!empty($result) && in_array($result[0]->DATA_TYPE, ['decimal', 'float', 'real'])) {
                $this->command->info('Column duration is already DECIMAL');
            } else {
                $this->command->info('Column duration does not exist or already changed');
            }
        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
}
