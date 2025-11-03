<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop foreign key constraint first (chỉ khi tồn tại)
            if (Schema::hasColumn('courses', 'teacher_id')) {
                try {
                    $table->dropForeign(['teacher_id']);
                } catch (\Exception $e) {
                    // Foreign key có thể không tồn tại, tiếp tục
                }
                
                // Drop the teacher_id column
                $table->dropColumn('teacher_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Add back the teacher_id column (chỉ khi chưa tồn tại)
            if (!Schema::hasColumn('courses', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->after('is_locked');
            }
        });
    }
};
