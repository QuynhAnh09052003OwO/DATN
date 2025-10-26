<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangePublishedToLockedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Check if is_locked column exists
            $hasIsLocked = Schema::hasColumn('courses', 'is_locked');
            $hasIsPublished = Schema::hasColumn('courses', 'is_published');
            
            $this->command->info('has is_locked: ' . ($hasIsLocked ? 'YES' : 'NO'));
            $this->command->info('has is_published: ' . ($hasIsPublished ? 'YES' : 'NO'));
            
            if ($hasIsPublished && !$hasIsLocked) {
                // Find and drop constraint first
                $query = "SELECT name FROM sys.default_constraints WHERE parent_object_id = OBJECT_ID('courses') AND parent_column_id = COLUMNPROPERTY(OBJECT_ID('courses'), 'is_published', 'ColumnId')";
                $constraints = DB::select($query);
                
                foreach ($constraints as $constraint) {
                    DB::statement("ALTER TABLE courses DROP CONSTRAINT {$constraint->name}");
                    $this->command->info('Dropped constraint: ' . $constraint->name);
                }
                
                // Drop column and add new column
                DB::statement('ALTER TABLE courses DROP COLUMN is_published');
                DB::statement('ALTER TABLE courses ADD is_locked bit NOT NULL DEFAULT 1');
                $this->command->info('Column renamed successfully from is_published to is_locked');
            } elseif ($hasIsLocked) {
                $this->command->info('Column is_locked already exists');
            } else {
                $this->command->info('Neither column exists');
            }
        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
        }
    }
}
