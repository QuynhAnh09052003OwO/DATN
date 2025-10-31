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
            // Drop foreign key constraint first
            $table->dropForeign(['teacher_id']);
            
            // Drop the teacher_id column
            $table->dropColumn('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Add back the teacher_id column
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->after('is_locked');
        });
    }
};
